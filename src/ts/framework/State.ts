import { Game } from '../Game';
import { onClick } from './event';
import { irreversibleAction } from './engine';

export class State {
  game: Game;
  bga: ExtendedBga;

  constructor(game: Game, bga: ExtendedBga) {
    this.game = game;
    this.bga = bga;
  }

  actionOnClick(node: Element | string, action: string, args?: any, isIrreversibleAction: boolean = false) {
    const targetNode = typeof node === 'string' ? $(node) : node;
    if (!targetNode) {
      console.error(`Node not found: ${node}`);
      return;
    }

    const callback = () => {
      this.bga.actions.performAction(action, args);
    };

    onClick(targetNode, isIrreversibleAction ? irreversibleAction(callback) : callback);
  }

  addActionBtn(label: string, action: string, args?: any, params?: any) {
    const callback = () => {
      this.bga.actions.performAction(action, args);
    };
    this.bga.statusBar.addActionButton(label, callback, params);
  }

  addCancelStateBtn(label?: string) {
    if (label === null) label = _('Cancel');

    this.bga.statusBar.addActionButton(
      label,
      () => {
        this.bga.states.restoreServerGameState();
      },
      { color: 'secondary' }
    );
  }
}
