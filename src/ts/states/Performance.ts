import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';

/**
 * Performance visual state.
 *
 * The active player must choose which active performance card to resolve.
 * Performance cards are already rendered on the board by Cards.ts's
 * setupPerformanceCards. Highlight the selectable ones and let the player
 * click one. After selection, dim the chosen card and remove highlights
 * from others.
 */
export class Performance {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: PerformanceArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: PerformanceArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: PerformanceArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: PerformanceArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        if (!args.availablePerformances.length) {
            this.bga.statusBar.addActionButton(
                _('No performances available'),
                () => {},
                { disabled: true }
            );
            return;
        }

        // Highlight performance cards on the board
        let highlighted = false;
        for (const perf of args.availablePerformances) {
            const el = $(`performance-${perf.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                clearPossible();
                this._cleanup();
                this.bga.statusBar.removeActionButtons();
                // Mark this performance as chosen (visual feedback)
                el.classList.add('chosen');
                // Make other non-chosen performances semi-transparent
                for (const otherPerf of args.availablePerformances) {
                    if (otherPerf.id !== perf.id) {
                        const otherEl = $(`performance-${otherPerf.id}`);
                        if (otherEl) otherEl.classList.add('dimmed');
                    }
                }
                this.bga.actions.performAction('actSelectPerformance', {
                    performanceId: perf.id,
                });
            });
        }

        // Fallback: action buttons if no DOM elements found
        if (!highlighted) {
            for (const perf of args.availablePerformances) {
                const name = perf.name ?? perf.type;
                this.bga.statusBar.addActionButton(
                    _(name),
                    () => {
                        clearPossible();
                        this._cleanup();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actSelectPerformance', {
                            performanceId: perf.id,
                        });
                    }
                );
            }
        }
    }

    async notif_performanceChosen(args: PerformanceChosenArgs) {
        // Notification sent to all players when a performance is selected.
        // Dim other performances and highlight the chosen one.
        clearPossible();

        // Remove selectable class from all performance cards
        document.querySelectorAll('[id^="performance-"]').forEach((el) => {
            el.classList.remove('selectable');
        });

        // Dim all performance cards first
        const gamedatas = this.bga.gameui.gamedatas as TrickerionGamedatas;
        for (const perf of gamedatas.performances.active) {
            const el = $(`performance-${perf.id}`);
            if (!el) continue;
            el.classList.add('dimmed');
        }

        // Highlight the chosen performance
        const chosenEl = $(`performance-${args.performance.id}`);
        if (chosenEl) {
            chosenEl.classList.remove('dimmed');
            chosenEl.classList.add('chosen');
        }
    }

    private _cleanup() {
        document.querySelectorAll('[id^="performance-"]').forEach((el) => {
            el.classList.remove('selectable');
            el.classList.remove('chosen');
            el.classList.remove('dimmed');
        });
    }
}
