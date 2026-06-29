import { Game } from '../Game';
import { cards } from '../Cards';
import { attachRegisteredTooltips, clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { board } from '../Board';

/**
 * Map character idle location → assignment slot DOM suffix.
 */
const IDLE_TO_SLOT: Record<string, string> = {
    'idle-player-board': 'magician',
    'idle-apprentice-1': 'apprentice-1',
    'idle-apprentice-2': 'apprentice-2',
    'idle-apprentice-3': 'apprentice-3',
    'idle-engineer-board': 'engineer',
    'idle-manager-board': 'manager',
    'idle-assistant-board': 'assistant',
};

export class AssignCharacters {
    game: Game;
    bga: ExtendedBga;
    private _selectedAssignmentId: number | null = null;
    private _selectedCharacterId: number | null = null;
    /** Map assignmentId → characterId for currently pending assignments (tracked in-memory) */
    private _pendingAssignments: Map<number, number> = new Map();

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: AssignCharactersArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: AssignCharactersArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: AssignCharactersArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();
        this._selectedAssignmentId = null;
        this._selectedCharacterId = null;

        // Rebuild pending map from args
        this._pendingAssignments = new Map();
        for (const e of args.pendingAssignments) {
            this._pendingAssignments.set(e.assignmentId, e.characterId);
        }

        const playerId = this.bga.players.getCurrentPlayerId();
        const pending = $('trickerion-pending');
        pending.innerHTML = '';

        /* ── 1. Build charId → idleLocation lookup ── */
        const charIdToIdle: Record<number, string> = {};
        for (const ch of this.game.gamedatas.characters.visible) {
            if (ch.playerId === playerId) charIdToIdle[ch.id] = ch.idleLocation;
        }

        /* ── 2. Render already-pending cards in their slots (from args) ── */
        const pendingInSlot = new Set<string>();

        for (const entry of args.pendingAssignments) {
            const assignment =
                args.availableAssignments.find((a) => a.id === entry.assignmentId) ||
                this.game.gamedatas.assignments.hand.find((a) => a.id === entry.assignmentId);
            if (!assignment) continue;

            const idleLoc = charIdToIdle[entry.characterId];
            const suffix = idleLoc ? IDLE_TO_SLOT[idleLoc] : null;
            if (!suffix) continue;

            const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
            if (!slotHolder) continue;
            const slot = slotHolder.querySelector('.assignment-slot') as HTMLElement;
            if (!slot) continue;

            slot.innerHTML = cards.tplAssignmentCard(assignment);
            slot.dataset.assignmentId = String(entry.assignmentId);
            slot.dataset.characterId = String(entry.characterId);
            slot.classList.add('assigned');
            pendingInSlot.add(suffix);

            onClick(slot, () => {
                this.bga.actions.performAction('actUnassignCharacter', { assignmentId: entry.assignmentId });
            });

            attachRegisteredTooltips();
        }

        /* ── 3. Available character slots (not in pending) ── */
        for (const character of args.availableCharacters) {
            const suffix = IDLE_TO_SLOT[character.idleLocation];
            if (!suffix || pendingInSlot.has(suffix)) continue;

            const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
            if (!slotHolder) continue;
            const slot = slotHolder.querySelector('.assignment-slot') as HTMLElement;
            if (!slot) continue;
            const holder = slot.parentElement as HTMLElement;
            holder.classList.add('slot-available');

            onClick(slot, () => {
                if (this._selectedAssignmentId !== null) {
                    clearPossible();
                    this.bga.actions.performAction('actAssignCharacter', {
                        assignmentId: this._selectedAssignmentId,
                        characterId: character.id,
                    });
                    return;
                }

                if (this._selectedCharacterId === character.id) {
                    slot.classList.remove('selected');
                    this._selectedCharacterId = null;
                } else {
                    if (this._selectedCharacterId !== null) {
                        const prevSuffix =
                            IDLE_TO_SLOT[
                                args.availableCharacters.find((c) => c.id === this._selectedCharacterId)?.idleLocation ?? ''
                            ];
                        if (prevSuffix) {
                            const prevHolder = $(`assignment-slot-${playerId}-${prevSuffix}`);
                            if (prevHolder) prevHolder.querySelector('.assignment-slot')?.classList.remove('selected');
                        }
                    }
                    slot.classList.add('selected');
                    this._selectedCharacterId = character.id;
                }
            });
        }

        /* ── 4. Hand cards (not in pending) ── */
        for (const assignment of args.availableAssignments) {
            if (this._pendingAssignments.has(assignment.id)) continue;

            if ($(`assignment-card-${assignment.id}`)) {
                pending.insertAdjacentElement('beforeend', $(`assignment-card-${assignment.id}`));
            } else {
                pending.insertAdjacentHTML('beforeend', cards.tplAssignmentCard(assignment));
            }
            const cardEl = $(`assignment-card-${assignment.id}`);
            if (!cardEl) continue;

            onClick(cardEl, () => {
                if (this._selectedCharacterId !== null) {
                    clearPossible();
                    this.bga.actions.performAction('actAssignCharacter', {
                        assignmentId: assignment.id,
                        characterId: this._selectedCharacterId,
                    });
                    return;
                }

                if (this._selectedAssignmentId === assignment.id) {
                    cardEl.classList.remove('selected');
                    this._selectedAssignmentId = null;
                } else {
                    if (this._selectedAssignmentId !== null) {
                        const prev = $(`assignment-card-${this._selectedAssignmentId}`);
                        if (prev) prev.classList.remove('selected');
                    }
                    cardEl.classList.add('selected');
                    this._selectedAssignmentId = assignment.id;
                }
            });
        }

        attachRegisteredTooltips();

        /* ── 5. Buttons ── */
        this.bga.statusBar.addActionButton(_('Done'), () => {
            clearPossible();
            this.bga.actions.performAction('actDone', {});
        });

        this.bga.statusBar.addActionButton(
            _('Reset'),
            () => {
                clearPossible();
                this.bga.actions.performAction('actReset', {});
            },
            { color: 'secondary' }
        );
    }

    // ──────────────────────────────────────────────────────
    //  Notification handlers (animated single-card ops)
    // ──────────────────────────────────────────────────────

    async notif_assignmentPending(args: AssignmentPendingArgs) {
        // Only handle notifications for the current player
        if (args.player_id !== this.bga.players.getCurrentPlayerId()) return;

        const playerId = args.player_id;
        const assignmentId = args.assignment.id;
        const characterId = args.characterId;

        // Track in memory
        this._pendingAssignments.set(assignmentId, characterId);

        // Find the card element in the hand
        const cardEl = $(`assignment-card-${assignmentId}`);
        if (!cardEl) return;

        // Find the target slot
        const character = this.game.gamedatas.characters.visible.find((c) => c.id === characterId);
        if (!character) return;

        const suffix = IDLE_TO_SLOT[character.idleLocation];
        if (!suffix) return;

        const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
        if (!slotHolder) return;
        const slot = slotHolder.querySelector('.assignment-slot') as HTMLElement;
        if (!slot) return;

        // Mark the slot as having a card now (remove slot-available, add assigned)
        const holder = slot.parentElement as HTMLElement;
        holder.classList.remove('slot-available');

        // Slide animation: card from pending area → slot
        const animManager = await board.getAnimationManager();
        await animManager.slideAndAttach(cardEl, slot, {
            duration: 800,
            toPlaceholder: 'on',
            fromPlaceholder: 'on',
            preserveScale: false,
        });
    }

    async notif_unassignmentPending(args: UnassignmentPendingArgs) {
        const assignmentId = args.assignment.id;
        const cardEl = $(`assignment-card-${assignmentId}`);
        if (!cardEl) return;

        const pending = $('trickerion-pending');
        const animManager = await board.getAnimationManager();
        await animManager.slideAndAttach(cardEl, pending, {
            duration: 800,
            toPlaceholder: 'off',
            fromPlaceholder: 'on',
            preserveScale: false,
        });
    }
}
