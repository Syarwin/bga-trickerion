import { Game } from '../Game';
import { addCustomTooltip, getPersistantActionButtonsNode, getRestartActionButtonsNode, isCurrentPlayerActive } from './utils';

export class StateProcessor {
  game: Game;
  bga: ExtendedBga;

  constructor(game: Game, bga: ExtendedBga) {
    this.game = game;
    this.bga = bga;
  }

  process(args: GenericArguments, stateArgs: Gamestate) {
    if (args?.customStateDescription) {
      this.changePageTitle(args.customStateDescription);
    }

    if (this.bga.gameui.isCurrentPlayerActive()) {
      if (args?.optionalAction) {
        this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Skip'), () => {
          this.bga.actions.performAction('actPassOptionalAction', {});
        });
      }

      if (args?.previousEngineChoices >= 1 && !args.automaticAction) {
        if (args?.previousSteps) {
          let lastStep = Math.max(...args.previousSteps);
          if (lastStep > 0) {
            // let label = `<i class="fa fa-step-backward" aria-hidden="true"></i>&nbsp;&nbsp;${_('Undo')}`;
            let label = `<i class="fa fa-step-backward" aria-hidden="true"></i>`;

            this.bga.statusBar.addAttachedActionButton(
              getRestartActionButtonsNode(),
              label,
              () => {
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actUndoToStep', { stepId: lastStep });
              },
              { color: 'alert' }
            );
          }
        }

        // let label = `<i class="fa fa-fast-backward" aria-hidden="true"></i>&nbsp;&nbsp;${_('Restart')}`;
        let label = `<i class="fa fa-fast-backward" aria-hidden="true"></i>`;

        this.bga.statusBar.addAttachedActionButton(
          getRestartActionButtonsNode(),
          label,
          () => {
            this.bga.statusBar.removeActionButtons();
            this.bga.actions.performAction('actRestart', {});
          },
          { color: 'alert' }
        );
      }
    }

    if (args?.anytimeActions && args?.anytimeActions.length > 0 && stateArgs.name !== 'client_selectAnytimeAction') {
      this.bga.statusBar.addAttachedActionButton(
        getRestartActionButtonsNode(),
        `<i class="fa fa-magic" aria-hidden="true"></i>`, //_('Free actions'),
        this.openAnytimeActions(args.anytimeActions).bind(this),
        { id: 'freeActionsBtn' }
      );
      addCustomTooltip($('freeActionsBtn'), _('Free actions'));
    }

    if (this.bga.gameui.gamedatas.bgaEnvironment == 'studio') {
      let button = this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Show Engine'), () => {
        this.bga.actions.performAction('actShowEngine', { previous: false }, { checkAction: false });
      });
      button.style.setProperty('float', 'right');
      button = this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Show Previous Engine'), () => {
        this.bga.actions.performAction('actShowEngine', { previous: true }, { checkAction: false });
      });
      button.style.setProperty('float', 'right');
    }
  }

  changePageTitle = function (this: StateProcessor, customStateDescription: StateDescription) {
    let currentState = this.bga.gameui.gamedatas.gamestate;
    if (currentState.private_state) {
      currentState = this.bga.gameui.gamedatas.gamestate.private_state;
    }

    if (customStateDescription.descriptionMyTurn) {
      currentState.descriptionmyturn = customStateDescription.descriptionMyTurn;
    }

    if (customStateDescription.description) {
      currentState.description = customStateDescription.description;
    }

    this.bga.statusBar.setTitle(isCurrentPlayerActive() ? currentState.descriptionmyturn : currentState.description);
  };

  openAnytimeActions(this: StateProcessor, anytimeActions: AnytimeAction[]) {
    return () => {
      this.bga.states.setClientState('client_selectAnytimeAction', {
        descriptionmyturn: _('${you} may choose an action to perform'),
        args: { anytimeActions },
      });
    };
  }
}
