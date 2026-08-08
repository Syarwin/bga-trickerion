import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { formatIcon } from '../format';
import { staticData } from '../staticData';

export class DiscardTrick {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: DiscardTrickArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: DiscardTrickArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: DiscardTrickArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        for (const trick of args.availableTricks) {
            // Get category from static data if not on the model
            const cat = trick.category ?? (staticData.tricks[trick.type] as any)?.category;
            const icon = cat ? formatIcon(cat) : '';
            const name = (staticData.tricks[trick.type] as any)?.name ?? trick.type;
            const label = `${icon} ${_(name)}`;

            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actDiscardTrick', { trickId: trick.id });
                }
            );
        }
    }
}
