import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';
import { staticData } from '../staticData';

/**
 * SetupTrick visual state — 4-phase wizard:
 *   1. Select performance card (click on board)
 *   2. Select trick to place (click trick card on player board)
 *   3. Select slot on the performance (click slot on performance card)
 *   4. Select direction (button — limited options, usually 1-3 per slot)
 *
 * Each phase has a Back button to return to the previous phase,
 * and the entire flow has a Cancel to leave the state.
 */
export class SetupTrick {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: SetupTrickArgs | null = null;

    /** Phase state */
    private _selectedPerformanceId: string | null = null;
    private _selectedTrick: Trick | null = null;
    private _selectedSlotId: string | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: SetupTrickArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: SetupTrickArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: SetupTrickArgs, isCurrentPlayerActive: boolean) {
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

        this._resetSelection();
        this._showPhase1(args);
    }

    // ============================================
    // Phase 1: Select a performance card
    // ============================================
    private _showPhase1(args: SetupTrickArgs) {
        this.bga.statusBar.removeActionButtons();

        let highlighted = false;
        for (const perf of args.availablePerformances) {
            // Check if this performance actually has any tricks + slots available
            const perfData = args.possibleTricksAndSlots[perf.id];
            if (!perfData || !perfData.possibleTricks.length) continue;

            const el = $(`performance-${perf.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                this._selectedPerformanceId = String(perf.id);
                this._showPhase2(args, perf);
            });
        }

        // Fallback: buttons
        if (!highlighted) {
            for (const perf of args.availablePerformances) {
                const perfData = args.possibleTricksAndSlots[perf.id];
                if (!perfData || !perfData.possibleTricks.length) continue;

                const name = perf.name ?? perf.type;
                this.bga.statusBar.addActionButton(
                    _(name),
                    () => {
                        this._selectedPerformanceId = String(perf.id);
                        this._showPhase2(args, perf);
                    }
                );
            }
        }

        // Cancel
        if (highlighted) {
            this.bga.statusBar.addActionButton(
                _('Cancel'),
                () => {
                    clearPossible();
                    this._cleanup();
                    this.bga.statusBar.removeActionButtons();
                },
                { color: 'alert' }
            );
        }
    }

    // ============================================
    // Phase 2: Select a trick to place on the performance
    // ============================================
    private _showPhase2(args: SetupTrickArgs, perf: Performance) {
        this._selectedTrick = null;
        this._selectedSlotId = null;
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        const perfData = args.possibleTricksAndSlots[perf.id];
        if (!perfData || !perfData.possibleTricks.length) {
            this._goBackToPhase1(args);
            return;
        }

        let highlighted = false;
        for (const trick of perfData.possibleTricks) {
            const el = $(`trick-${trick.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                this._selectedTrick = trick;
                this._showPhase3(args, perf, trick);
            });
        }

        // Fallback: action buttons
        if (!highlighted) {
            for (const trick of perfData.possibleTricks) {
                const cat = trick.category ?? (staticData.tricks[trick.type] as any)?.category;
                const icon = cat ? formatIcon(cat) : '';
                const name = (staticData.tricks[trick.type] as any)?.name ?? trick.type;
                const label = `${icon} ${_(name)}`;

                this.bga.statusBar.addActionButton(
                    label,
                    () => {
                        this._selectedTrick = trick;
                        this._showPhase3(args, perf, trick);
                    }
                );
            }
        }

        // Back to phase 1
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                this._goBackToPhase1(args);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Phase 3: Select a slot on the performance card
    // ============================================
    private _showPhase3(args: SetupTrickArgs, perf: Performance, trick: Trick) {
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        const perfData = args.possibleTricksAndSlots[perf.id];
        if (!perfData) {
            this._goBackToPhase2(args, perf);
            return;
        }

        const availableSlots = perfData.possibleSlots;
        const slotIds = Object.keys(availableSlots);
        if (!slotIds.length) {
            this._goBackToPhase2(args, perf);
            return;
        }

        // Highlight available slots on the performance card
        let highlighted = false;
        for (const slotId of slotIds) {
            const slot = availableSlots[slotId];
            // Find the slot element inside the performance card
            const perfEl = $(`performance-${perf.id}`);
            if (!perfEl) continue;

            const slotEl = perfEl.querySelector(`.trick-marker-slot[data-slotid="${slotId}"]`) as HTMLElement;
            if (!slotEl) continue;

            slotEl.classList.add('selectable');
            highlighted = true;

            onClick(slotEl, () => {
                this._selectedSlotId = slotId;
                this._showPhase4(args, perf, trick, slotId, slot.links);
            });
        }

        // Fallback: action buttons
        if (!highlighted) {
            for (const slotId of slotIds) {
                this.bga.statusBar.addActionButton(
                    _('Slot ${id}').replace('${id}', slotId),
                    () => {
                        this._selectedSlotId = slotId;
                        this._showPhase4(args, perf, trick, slotId, availableSlots[slotId].links);
                    }
                );
            }
        }

        // Back to phase 2
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                this._goBackToPhase2(args, perf);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Phase 4: Select direction (link) for the slot
    // ============================================
    private _showPhase4(
        args: SetupTrickArgs,
        perf: Performance,
        trick: Trick,
        slotId: string,
        links: Link[]
    ) {
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        if (!links.length) {
            // No direction options — auto-resolve with empty direction?
            this.bga.actions.performAction('actSetupTrick', {
                trickId: trick.id,
                performanceId: perf.id,
                slotId,
                direction: '',
            });
            return;
        }

        for (const link of links) {
            const dirIcon = this._directionIcon(link.direction);
            const shardLabel = link.shard ? ` (${formatIcon('shard')})` : '';
            const label = `${dirIcon} ${_(link.direction)}${shardLabel}`;

            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actSetupTrick', {
                        trickId: trick.id,
                        performanceId: perf.id,
                        slotId,
                        direction: link.direction,
                    });
                }
            );
        }

        // Back to phase 3
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                this._showPhase3(args, perf, trick);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Navigation helpers
    // ============================================
    private _goBackToPhase1(args: SetupTrickArgs) {
        clearPossible();
        this._resetSelection();
        this._showPhase1(args);
    }

    private _goBackToPhase2(args: SetupTrickArgs, perf: Performance) {
        clearPossible();
        this._selectedTrick = null;
        this._selectedSlotId = null;
        this._showPhase2(args, perf);
    }

    private _resetSelection() {
        this._selectedPerformanceId = null;
        this._selectedTrick = null;
        this._selectedSlotId = null;
    }

    // ============================================
    // Cleanup
    // ============================================
    private _cleanup() {
        this._resetSelection();

        // Remove selectable from all performance cards
        document.querySelectorAll('[id^="performance-"]').forEach((el) => {
            el.classList.remove('selectable');
        });

        // Remove selectable from all trick cards
        if (this._currentArgs) {
            for (const perf of this._currentArgs.availablePerformances) {
                const perfData = this._currentArgs.possibleTricksAndSlots[perf.id];
                if (!perfData) continue;
                for (const trick of perfData.possibleTricks) {
                    const el = $(`trick-${trick.id}`);
                    if (el) el.classList.remove('selectable');
                }
            }
        }

        // Remove selectable from all slot elements
        document.querySelectorAll('.trick-marker-slot').forEach((el) => {
            el.classList.remove('selectable');
        });
    }

    /**
     * Return a small direction arrow icon for display.
     */
    private _directionIcon(direction: string): string {
        switch (direction) {
            case 'up': return '↑';
            case 'down': return '↓';
            case 'left': return '←';
            case 'right': return '→';
            default: return '?';
        }
    }
}
