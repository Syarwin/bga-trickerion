import { Game } from '../Game';
import { cards } from '../Cards';
import { attachRegisteredTooltips, onSelectN } from '../framework/utils';

export class ChooseMagician {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: ChooseMagicianArgs, isCurrentPlayerActive: boolean) {
        if (!isCurrentPlayerActive) return;
        const isDarkAlley = this.game.gamedatas.globals.isDarkAlley;

        let elements = {};
        for (const magician of args.availableMagicians) {
            $('trickerion-pending').insertAdjacentHTML('beforeend', cards.tplMagician(magician, '', isDarkAlley));
            elements[magician.id] = $(`magician-${magician.id}`);
        }
        attachRegisteredTooltips();

        onSelectN({
            elements,
            n: 1,
            callback: (selected) => {
                this.bga.actions.performAction('actChooseMagician', { magicianId: selected[0] });
            },
        });
    }

    onLeavingState(args: ChooseMagicianArgs, isCurrentPlayerActive: boolean) {
        $('trickerion-pending').innerHTML = '';
    }
}
