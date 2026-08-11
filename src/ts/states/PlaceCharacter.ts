import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick, detachAll } from '../framework/event';
import { board } from '../Board';
import { meeples } from '../Meeples';

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

    /** Get the DOM ID for a character's idle slot */
    private _getIdleElementId(character: Character): string | null {
        const playerId = character.playerId;
        if (!playerId) return null;
        
        const idleLoc = character.idleLocation;
        if (idleLoc === 'idle-player-board') {
            return `idle-${playerId}-magician`;
        } else if (idleLoc === 'idle-assistant-board' && character.type === 'apprentice') {
            return `idle-${playerId}-apprentice-assistant`;
        } else if (idleLoc.match(/^idle-(apprentice-\d+)$/)) {
            return `idle-${playerId}-${idleLoc.replace('idle-', '')}`;
        } else if (idleLoc.match(/^idle-(engineer|manager|assistant)-board$/)) {
            return `idle-${playerId}-${idleLoc.replace('idle-', '').replace('-board', '')}`;
        }
        return null;
    }

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

        // Make each available character meeple clickable
        for (const entry of args.availableAssignments) {
            const meepleEl = meeples.getMeeple(entry.character);
            onClick(meepleEl, () => this._selectCharacter(entry));
        }
    }

    // ── Selection Logic ────────────────────────────────────

    private _selectCharacter(
        entry: PlaceCharacterArgs['availableAssignments'][0]) {
        // Deselect previous
        this._deselect();

        // Select this one
        const meepleEl = meeples.getMeeple(entry.character);
        const idleLoc = entry.character.idleLocation;
        const suffix = IDLE_TO_SLOT[idleLoc];
        const slotHolder = $(`assignment-slot-${entry.character.playerId}-${suffix}`);
        
        // Add selected class to the meeple element
        this._selectedCharacterId = entry.character.id;
        meepleEl.classList.add('selected');
        this._selectedSlotHolder = slotHolder;
        slotHolder.classList.add('selected');

        // Highlight possible board locations
        const playerId = getCurrentPlayerId();
        for (const locationId of entry.possibleLocations) {
            const el = $(this._resolveLocationDomId(locationId, playerId));
            if(!el){
                continue;
            }
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
        
        // Remove selectable and selected from all character meeples
        document.querySelectorAll('.trickerion-meeple.meeple-character.selectable').forEach(el => {
            el.classList.remove('selectable');
        });
        
        document.querySelectorAll('.trickerion-meeple.meeple-character.selected').forEach(el => {
            el.classList.remove('selected');
        });
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
        return locationId;
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
        
        const character = args.character;
        const meepleEl = meeples.getMeeple(character);
        const targetEl = meeples.getMeepleContainer(character);
        
        // Use animation manager to slide the meeple
        const animManager = await board.getAnimationManager();
        await animManager.slideAndAttach(meepleEl, targetEl, {
            duration: 1200,
            toPlaceholder: 'off',
            fromPlaceholder: 'off',
            preserveScale: true,
        });
    }
}
