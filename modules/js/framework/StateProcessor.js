import { getRestartActionButtonsNode } from "./utils.js";

export class StateProcessor {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }

    process(args, stateArgs) {
        if (args?.customStateDescription) {
            this.changePageTitle(args.customStateDescription);
        }

        if (this.bga.gameui.isCurrentPlayerActive()) {
            if (args?.optionalAction) {
                this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Skip'), () => {
                    this.bga.actions.performAction('actPassOptionalAction', {})
                });
            }

            if (args?.anytimeActions && args?.anytimeActions.length > 0) {
                this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Free actions'), this.openAnytimeActions(args.anytimeActions).bind(this));
            }

            if (args?.previousEngineChoices >= 1 && !args.automaticAction) {
                if (args?.previousSteps) {
                    let lastStep = Math.max(...args.previousSteps);
                    if (lastStep > 0) {
                        let label = `<i class="fa fa-step-backward" aria-hidden="true"></i>&nbsp;&nbsp;${_("Undo")}`;
                        
                        this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), label, () =>  {
                            this.bga.statusBar.removeActionButtons();
                            this.bga.actions.performAction("actUndoToStep", { stepId: lastStep });
                        }, { color: 'alert' });
                    }
                }

                let label = `<i class="fa fa-fast-backward" aria-hidden="true"></i>&nbsp;&nbsp;${_("Restart")}`;
        
                this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), label, () => {
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction("actRestart", {});
                }, {color: 'alert' });
            }
        }

        if (this.bga.gameui.gamedatas.bgaEnvironment == "studio") {
            let button = this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Show Engine'), () => {
                this.bga.performAction("actShowEngine", { previous: false}, { checkAction: false });
            });
            button.style.setProperty('float', 'right');
            button = this.bga.statusBar.addAttachedActionButton(getRestartActionButtonsNode(), _('Show Previous Engine'), () => {
                this.bga.performAction("actShowEngine", { previous: true}, { checkAction: false });
            });
            button.style.setProperty('float', 'right');
        }
    }

    changePageTitle = function(customStateDescription, save = false) {
        let currentState = this.bga.gameui.gamedatas.gamestate;
        if (currentState.private_state) {
            currentState = this.bga.gameui.gamedatas.gamestate.private_state;
        }

        if (save) {
            currentState.descriptionmyturngeneric = currentState.descriptionmyturn;
            currentState.descriptiongeneric = currentState.description;
        }

        if (customStateDescription.descriptionMyTurn) {
            currentState.descriptionmyturn = customStateDescription.descriptionMyTurn;
        }

        if (customStateDescription.description) {
            currentState.description = customStateDescription.description;
        }

        this.bga.gameui.updatePageTitle(currentState);
    }

    openAnytimeActions(actions) {
        return () => {
            this.bga.states.setClientState("client_selectAnytimeAction", {
                descriptionmyturn: _("${you} may choose an action to perform"),
                args: {availableActions: actions }
            });
        }
    }

}