import { Game } from "../Game";
import { cards } from "../Cards";
import { attachRegisteredTooltips } from "../framework/utils";

/**
 * FinishSetup visual state.
 *
 * Transitional state at the end of setup. Renders initial pending prophecies
 * on the Dark Alley board (if Dark Alley expansion is active).
 */
export class FinishSetup {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(_args: object, _isCurrentPlayerActive: boolean) {
        // Transition state — UI setup is already complete from board.init()
    }

    onLeavingState(_args: object, _isCurrentPlayerActive: boolean) {
        // Nothing to clean up
    }

    onPlayerActivationChange(_args: object, _isCurrentPlayerActive: boolean) {
        // Game state — not player-interactive
    }

    async notif_pendingProphecies(args: PendingPropheciesArgs) {
        // Initial pending prophecies are revealed — render them in the Dark Alley area
        const container = document.getElementById('alley-pending-prophecies');
        if (!container) return;

        container.innerHTML = '';
        for (const prophecy of args.prophecies) {
            container.insertAdjacentHTML('beforeend', cards.tplProphecy(prophecy));
        }
        attachRegisteredTooltips();

        // Update gamedatas
        const gamedatas = this.bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.prophecies.pending = args.prophecies;
    }
}
