import { bga } from '../framework/utils';

const IDLE_TO_SLOT: Record<string, string> = {
    'idle-player-board': 'magician',
    'idle-apprentice-1': 'apprentice-1',
    'idle-apprentice-2': 'apprentice-2',
    'idle-apprentice-3': 'apprentice-3',
    'idle-engineer-board': 'engineer',
    'idle-manager-board': 'manager',
    'idle-assistant-board': 'assistant',
};

export class AssignmentNotifications {
    constructor() {}

    private _slotForCharacter(playerId: number, characterId: number): { slotHolder: Element; slot: HTMLElement } | null {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        const character = gamedatas?.characters?.visible?.find((c) => c.id === characterId);
        if (!character) return null;

        const suffix = IDLE_TO_SLOT[character.idleLocation];
        if (!suffix) return null;

        const slotHolder = $(`assignment-slot-${playerId}-${suffix}`);
        if (!slotHolder) return null;

        const slot = slotHolder.querySelector('.assignment-slot') as HTMLElement;
        if (!slot) return null;

        return { slotHolder, slot };
    }

    async notif_assignmentAssigned(args: AssignmentAssignedArgs) {
        const playerId = args.player_id;
        const characterId = args.characterId;
        const result = this._slotForCharacter(playerId, characterId);
        if (!result) return;

        const { slot, slotHolder } = result;

        if (slot.querySelector('.assignment-card')) {
            slot.classList.add('assigned');
            return;
        }

        const assignmentData = args._private?.assignment;
        const { cards } = await import('../Cards');
        if (assignmentData) {
            slot.innerHTML = cards.tplFacedownAssignmentCard(assignmentData);
        } else {
            slot.innerHTML = `<div class="assignment-card facedown"><div class="assignment-card-inner"><div class="card-back"></div></div></div>`;
        }

        slot.dataset.characterId = String(characterId);
        slot.classList.add('assigned');
        slotHolder.classList.remove('slot-available');
    }

    async notif_assignmentsReset(args: AssignmentResetArgs) {
        const playerId = args.player_id;

        if (args._private?.assignments) {
            for (const assignment of args._private.assignments) {
                const characterId = assignment.state;
                const result = this._slotForCharacter(playerId, characterId);
                if (!result) continue;

                const { slot, slotHolder } = result;
                slot.innerHTML = '';
                delete slot.dataset.assignmentId;
                delete slot.dataset.characterId;
                slot.classList.remove('assigned');
                slotHolder.classList.add('slot-available');
            }
        }
    }

    async notif_assignmentsReturned(_args: AssignmentReturnedArgs) {
        document.querySelectorAll('.assignment-slot.assigned').forEach((slot) => {
            slot.innerHTML = '';
            slot.classList.remove('assigned');
        });
    }

    async notif_assignmentsDiscarded(_args: AssignmentDiscardedArgs) {
        // Faceup special assignments go to the discard pile. Clear slots.
    }

    async assignmentsDrawn(_args: AssignmentsDrawnArgs) {}
    async assignmentsDiscarded(_args: AssignmentsDiscardedArgs) {}
}
