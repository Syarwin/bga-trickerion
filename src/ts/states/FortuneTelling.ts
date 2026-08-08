import { Game } from "../Game";
import { clearPossible, attachRegisteredTooltips, getCurrentPlayerId } from "../framework/utils";
import { onClick } from "../framework/event";
import { cards } from "../Cards";

/**
 * FortuneTelling visual state.
 *
 * Automatic action (auto-resolves) — all pending prophecies are rotated
 * clockwise (each moves 1 position forward in the pending area).
 * The notification `notif_propheciesUpdated` re-renders the pending prophecies
 * with a brief visual highlight on each.
 */
export class FortuneTelling {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(_args: object, _isCurrentPlayerActive: boolean) {
        // This is an automatic state — nothing to do on enter
    }

    onLeavingState(_args: object, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(_args: object, _isCurrentPlayerActive: boolean) {
        // Automatic action — no player interaction needed
    }

    async notif_propheciesUpdated(args: PropheciesUpdatedArgs) {
        // Re-render all pending prophecies with a brief rotation animation
        const container = document.getElementById('alley-pending-prophecies');
        if (!container) return;

        // Clear existing prophecies
        container.innerHTML = '';

        // Re-add each prophecy with a fade-in animation
        for (const prophecy of args.updatedProphecies) {
            const html = cards.tplProphecy(prophecy);
            container.insertAdjacentHTML('beforeend', html);

            // Add a brief rotation animation class
            const lastEl = container.lastElementChild as HTMLElement;
            if (lastEl) {
                lastEl.classList.add('fortune-rotated');
                setTimeout(() => {
                    lastEl.classList.remove('fortune-rotated');
                }, 600);
            }
        }

        attachRegisteredTooltips();

        // Update gamedatas for consistency
        const gamedatas = this.bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.prophecies.pending = args.updatedProphecies;
    }
}
