import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick, detachAll } from '../framework/event';

/**
 * Map backend CharacterLocation → DOM element ID for board slots.
 * All locations correspond directly to their DOM id, except workshop
 * which is player-specific (resolved at runtime).
 */
const BOARD_LOCATION_DOM_IDS: Record<string, string> = {
    'board-downtown-1': 'board-downtown-1',
    'board-downtown-2': 'board-downtown-2',
    'board-downtown-3': 'board-downtown-3',
    'board-downtown-4': 'board-downtown-4',
    'board-market-row-1': 'board-market-row-1',
    'board-market-row-2': 'board-market-row-2',
    'board-market-row-3': 'board-market-row-3',
    'board-market-row-4': 'board-market-row-4',
    'board-dark-alley-1': 'board-dark-alley-1',
    'board-dark-alley-2': 'board-dark-alley-2',
    'board-dark-alley-3': 'board-dark-alley-3',
    'board-dark-alley-4': 'board-dark-alley-4',
    'board-theater-thursday-basic-1': 'board-theater-thursday-basic-1',
    'board-theater-thursday-basic-2': 'board-theater-thursday-basic-2',
    'board-theater-thursday-magician': 'board-theater-thursday-magician',
    'board-theater-friday-basic-1': 'board-theater-friday-basic-1',
    'board-theater-friday-basic-2': 'board-theater-friday-basic-2',
    'board-theater-friday-magician': 'board-theater-friday-magician',
    'board-theater-saturday-basic-1': 'board-theater-saturday-basic-1',
    'board-theater-saturday-basic-2': 'board-theater-saturday-basic-2',
    'board-theater-saturday-magician': 'board-theater-saturday-magician',
    'board-theater-sunday-basic-1': 'board-theater-sunday-basic-1',
    'board-theater-sunday-basic-2': 'board-theater-sunday-basic-2',
    'board-theater-sunday-magician': 'board-theater-sunday-magician',
};

/** Map idle location → assignment slot suffix (same as AssignCharacters.IDLE_TO_SLOT) */
const IDLE_TO_SLOT: Record<string, string> = {
    'idle-player-board': 'magician',
    'idle-apprentice-1': 'apprentice-1',
    'idle-apprentice-2': 'apprentice-2',
    'idle-apprentice-3': 'apprentice-3',
    'idle-engineer-board': 'engineer',
    'idle-manager-board': 'manager',
    'idle-assistant-board': 'assistant',
};

export class PlaceCharacter {
    game: Game;
    bga: ExtendedBga;
    private _selectedCharacterId: number | null = null;
    private _selectedSlotHolder: Element | null = null;
    /** Store the full args so we can re-render on cancel without a server round-trip */
    private _currentArgs: PlaceCharacterArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: PlaceCharacterArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: PlaceCharacterArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: PlaceCharacterArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();

        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();
        const playerId = getCurrentPlayerId();

        // Make each available character slot clickable
        for (const entry of args.availableAssignments) {
            const idleLoc = entry.character.idleLocation;
            const suffix = IDLE_TO_SLOT[idleLoc];
            if (!suffix) continue;

            const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
            if (!slotHolder) continue;

            onClick(slotHolder, () => this._selectCharacter(entry, slotHolder));
        }
    }

    // ── Selection Logic ────────────────────────────────────

    private _selectCharacter(
        entry: PlaceCharacterArgs['availableAssignments'][0],
        slotHolder: Element
    ) {
        // Deselect previous
        this._deselect();

        // Select this one
        this._selectedCharacterId = entry.character.id;
        this._selectedSlotHolder = slotHolder;
        slotHolder.classList.add('selected');

        // Highlight possible board locations
        const playerId = getCurrentPlayerId();
        for (const locationId of entry.possibleLocations) {
            const domId = this._resolveLocationDomId(locationId, playerId);
            if (!domId) continue;
            const el = $(domId);
            if (!el) continue;

            el.classList.add('selectable');

            // Each location click fires the place action immediately
            onClick(el, () => {
                this.bga.actions.performAction('actPlace', {
                    characterId: entry.character.id,
                    locationId: locationId,
                });
            });
        }

        // "Leave idle" button for this character
        this.bga.statusBar.addActionButton(
            _('Leave idle'),
            () => {
                this.bga.actions.performAction('actLeaveIdle', {
                    characterId: entry.character.id,
                });
            },
            { color: 'secondary' }
        );

        // Cancel button to go back to initial state
        this.bga.statusBar.addActionButton(
            _('Cancel'),
            () => {
                const args: PlaceCharacterArgs = {
                    anytimeActions: [],
                    availableAssignments: this._getCurrentArgs(),
                };
                this.onPlayerActivationChange(args, true);
            },
            { color: 'alert' }
        );
    }

    private _deselect() {
        clearPossible();
        if (this._selectedSlotHolder) {
            this._selectedSlotHolder.classList.remove('selected');
        }
        this._cleanup();
    }

    private _cleanup() {
        this._selectedCharacterId = null;
        this._selectedSlotHolder = null;
    }

    /**
     * Resolve a backend CharacterLocation to a DOM element ID.
     * Workshop locations use per-player IDs: board-workshop-{playerId}-{n}.
     */
    private _resolveLocationDomId(locationId: string, playerId: number): string | null {
        const wsMatch = locationId.match(/^board-workshop-(\d+)$/);
        if (wsMatch) {
            return `board-workshop-${playerId}-${wsMatch[1]}`;
        }
        return BOARD_LOCATION_DOM_IDS[locationId] ?? null;
    }

    /**
     * Return the current available assignments from the stored args.
     * Used when re-rendering after cancel without a server round-trip.
     */
    private _getCurrentArgs(): PlaceCharacterArgs['availableAssignments'] {
        return this._currentArgs?.availableAssignments ?? [];
    }

    // ── Notification handlers ──────────────────────────────

    async notif_characterIdled(args: CharacterIdledArgs) {
        // Character was left idle — clear the assignment card from the slot
        const playerId = args.player_id;
        const idleLoc = args.character.idleLocation;
        const suffix = IDLE_TO_SLOT[idleLoc];
        if (!suffix) return;

        const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
        if (!slotHolder) return;
        const slot = slotHolder.querySelector('.assignment-slot') as HTMLElement;
        if (!slot) return;

        slot.innerHTML = '';
        delete slot.dataset.assignmentId;
        delete slot.dataset.characterId;
        slot.classList.remove('assigned');
    }

    async notif_characterPlaced(args: CharacterPlacedArgs) {
        // Deselect after placement so UI is clean for the next placement
        this._deselect();
    }
}
