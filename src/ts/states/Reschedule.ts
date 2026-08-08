import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';

/**
 * Reschedule visual state — 4-phase wizard:
 *   1. Select a trick marker (button — trick markers on performances may not have reliable DOM)
 *   2. Select target performance card (click on board)
 *   3. Select target slot (click on performance card)
 *   4. Select direction (button)
 *
 * Each phase has a Back button, and the entire flow has Cancel.
 */
export class Reschedule {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: RescheduleArgs | null = null;

    private _selectedTrickMarker: TrickMarker | null = null;
    private _selectedPerformanceId: string | null = null;
    private _selectedSlotId: string | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: RescheduleArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: RescheduleArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: RescheduleArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        if (!args.availableTrickMarkers.length) {
            this.bga.statusBar.addActionButton(
                _('No trick markers to reschedule'),
                () => {},
                { disabled: true }
            );
            return;
        }

        this._resetSelection();
        this._showPhase1(args);
    }

    // ============================================
    // Phase 1: Select a trick marker to move
    // ============================================
    private _showPhase1(args: RescheduleArgs) {
        this.bga.statusBar.removeActionButtons();

        // Trick markers on performances don't have reliable DOM elements yet
        // (see TODO 3b — they fall back to #trickerion-pending).
        // Always use buttons here, labeled with trick name + performance info.
        for (const tm of args.availableTrickMarkers) {
            // Try to find the trick name from gamedatas
            const trick = this._findTrickForMarker(tm);
            const trickName = trick?.name ?? trick?.type ?? String(tm.id);
            const cat = trick?.category;
            const icon = cat ? formatIcon(cat) : '';
            const label = `${icon} ${_(trickName)}`;

            this.bga.statusBar.addActionButton(
                label,
                () => {
                    this._selectedTrickMarker = tm;
                    this._showPhase2(args, tm);
                }
            );
        }

        // Cancel
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

    // ============================================
    // Phase 2: Select target performance card
    // ============================================
    private _showPhase2(args: RescheduleArgs, tm: TrickMarker) {
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        const perfData = args.possiblePerformances[tm.id];
        if (!perfData || !Object.keys(perfData).length) {
            this._goBackToPhase1(args);
            return;
        }

        let highlighted = false;
        for (const [perfId, data] of Object.entries(perfData)) {
            const perf = data.performance;

            const el = $(`performance-${perf.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                this._selectedPerformanceId = String(perf.id);
                this._showPhase3(args, tm, perf, data.possibleSlots);
            });
        }

        // Fallback: buttons
        if (!highlighted) {
            for (const [perfId, data] of Object.entries(perfData)) {
                const perf = data.performance;
                const name = perf.name ?? perf.type;
                this.bga.statusBar.addActionButton(
                    _(name),
                    () => {
                        this._selectedPerformanceId = String(perf.id);
                        this._showPhase3(args, tm, perf, data.possibleSlots);
                    }
                );
            }
        }

        // Back
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                this._goBackToPhase1(args);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Phase 3: Select target slot on performance
    // ============================================
    private _showPhase3(
        args: RescheduleArgs,
        tm: TrickMarker,
        perf: Performance,
        possibleSlots: Slot[]
    ) {
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        const slotIds = possibleSlots ? Object.keys(possibleSlots) : [];
        if (!slotIds.length) {
            this._goBackToPhase2(args, tm);
            return;
        }

        // Highlight available slots on the performance card
        let highlighted = false;
        for (const slotId of slotIds) {
            const slot = possibleSlots[slotId];
            const perfEl = $(`performance-${perf.id}`);
            if (!perfEl) continue;

            const slotEl = perfEl.querySelector(`.trick-marker-slot[data-slotid="${slotId}"]`) as HTMLElement;
            if (!slotEl) continue;

            slotEl.classList.add('selectable');
            highlighted = true;

            onClick(slotEl, () => {
                this._selectedSlotId = slotId;
                this._showPhase4(args, tm, perf.id, slotId, slot.links);
            });
        }

        // Fallback: buttons
        if (!highlighted) {
            for (const slotId of slotIds) {
                this.bga.statusBar.addActionButton(
                    _('Slot ${id}').replace('${id}', slotId),
                    () => {
                        this._selectedSlotId = slotId;
                        this._showPhase4(args, tm, perf.id, slotId, possibleSlots[slotId].links);
                    }
                );
            }
        }

        // Back
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                this._goBackToPhase2(args, tm);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Phase 4: Select direction + resolve
    // ============================================
    private _showPhase4(
        args: RescheduleArgs,
        tm: TrickMarker,
        performanceId: number,
        slotId: string,
        links: Link[]
    ) {
        clearPossible();
        this.bga.statusBar.removeActionButtons();

        if (!links.length) {
            this.bga.actions.performAction('actRescheduleTrick', {
                trickMarkerId: tm.id,
                performanceId,
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
                    this.bga.actions.performAction('actRescheduleTrick', {
                        trickMarkerId: tm.id,
                        performanceId,
                        slotId,
                        direction: link.direction,
                    });
                }
            );
        }

        // Back
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                if (this._selectedPerformanceId) {
                    const perfData = args.possiblePerformances[tm.id];
                    if (perfData && perfData[this._selectedPerformanceId]) {
                        this._showPhase3(args, tm, perfData[this._selectedPerformanceId].performance, perfData[this._selectedPerformanceId].possibleSlots);
                        return;
                    }
                }
                this._goBackToPhase2(args, tm);
            },
            { color: 'alert' }
        );
    }

    // ============================================
    // Helpers
    // ============================================
    private _goBackToPhase1(args: RescheduleArgs) {
        clearPossible();
        this._resetSelection();
        this._showPhase1(args);
    }

    private _goBackToPhase2(args: RescheduleArgs, tm: TrickMarker) {
        clearPossible();
        this._selectedPerformanceId = null;
        this._selectedSlotId = null;
        this._showPhase2(args, tm);
    }

    private _resetSelection() {
        this._selectedTrickMarker = null;
        this._selectedPerformanceId = null;
        this._selectedSlotId = null;
    }

    private _cleanup() {
        this._resetSelection();
        document.querySelectorAll('[id^="performance-"]').forEach((el) => {
            el.classList.remove('selectable');
        });
        document.querySelectorAll('.trick-marker-slot').forEach((el) => {
            el.classList.remove('selectable');
        });
    }

    private _directionIcon(direction: string): string {
        switch (direction) {
            case 'up': return '↑';
            case 'down': return '↓';
            case 'left': return '←';
            case 'right': return '→';
            default: return '?';
        }
    }

    /**
     * Try to find the Trick associated with a trick marker.
     * Iterates over all player tricks + available tricks.
     */
    private _findTrickForMarker(tm: TrickMarker): Trick | undefined {
        const allTricks: Trick[] = [];

        // Player tricks
        const playerTricks = this.game.gamedatas?.tricks?.player;
        if (playerTricks) {
            for (const tricks of Object.values(playerTricks)) {
                allTricks.push(...tricks);
            }
        }

        // Available tricks
        const available = this.game.gamedatas?.tricks?.available;
        if (available) {
            allTricks.push(...available);
        }

        return allTricks.find((t) => t.id === tm.trickId);
    }
}
