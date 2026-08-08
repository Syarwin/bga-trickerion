import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { formatIcon } from '../format';

export class DiscardComponents {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: DiscardComponentArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: DiscardComponentArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: DiscardComponentArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        for (const component of args.availableComponents) {
            const label = `${formatIcon(component.type)} ${_(component.type)}${component.count > 1 ? ` ×${component.count}` : ''}`;
            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actDiscardComponent', { componentId: component.id });
                }
            );
        }
    }

    async notif_componentDiscarded(args: ComponentDiscardedArgs) {
        // Remove the meeple element from the DOM
        const meepleId = `meeple-${args.component.type}-${args.component.id}`;
        const el = $(meepleId);
        if (el) {
            el.style.transition = 'opacity 0.4s, transform 0.4s';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.5)';
            setTimeout(() => el.remove(), 400);
        }
    }
}
