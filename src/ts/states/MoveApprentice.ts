import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';

/**
 * Map idle location suffix → DOM element id prefix
 * e.g. idle-apprentice-1 → #idle-{playerId}-apprentice-1
 */
const APPRENTICE_SLOT_SUFFIXES = ['apprentice-1', 'apprentice-2', 'apprentice-3'];

export class MoveApprentice {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: MoveApprenticeArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: MoveApprenticeArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: MoveApprenticeArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        if (!args.isSlotAvailable) {
            this.bga.statusBar.addActionButton(
                _('No slot available on assistant board'),
                () => {},
                { disabled: true }
            );
            return;
        }

        const playerId = getCurrentPlayerId();

        // Build a set of idle locations for quick lookup
        const apprenticeLocations = new Set<string>();
        for (const apprentice of args.availableApprentices) {
            apprenticeLocations.add(apprentice.idleLocation);
        }

        // Highlight each apprentice slot that has an available apprentice
        for (const suffix of APPRENTICE_SLOT_SUFFIXES) {
            const idleLoc = `idle-${suffix}`;
            if (!apprenticeLocations.has(idleLoc)) continue;

            const domId = `idle-${playerId}-${suffix}`;
            const el = $(domId);
            if (!el) continue;

            el.classList.add('selectable');
            onClick(el, () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();

                // Find the apprentice with this idleLocation
                const apprentice = args.availableApprentices.find((a) => a.idleLocation === idleLoc);
                if (!apprentice) return;

                this.bga.actions.performAction('actMoveApprentice', { apprenticeId: apprentice.id });
            });
        }
    }
}
