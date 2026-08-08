import { Game } from "../Game";
import { cards } from "../Cards";
import { attachRegisteredTooltips } from "../framework/utils";

/**
 * StartAssignment visual state.
 *
 * MULTIPLE_ACTIVE_PLAYER state that waits for all players to finish
 * assigning characters. Non-active players see each other's assignment
 * progress (revealed cards + facedown slots for hidden ones).
 * The active player can click "I want to change my assignments" to
 * re-enter the AssignCharacters private state.
 */
export class StartAssignment {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: AssignCharactersArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: object, _isCurrentPlayerActive: boolean) {
        this._cleanup();
    }

    onPlayerActivationChange(_args: AssignCharactersArgs, isCurrentPlayerActive: boolean) {
        this._cleanup();

        this.bga.statusBar.removeActionButtons();

        if (!isCurrentPlayerActive) {
            // Show other players' assignment progress
            this._renderOtherPlayersAssignments();
            // Allow current player to request re-entry to assignment
            this.bga.statusBar.addActionButton(
                _("I want to change my assignments"),
                () => {
                    this.bga.actions.performAction("actChangeAssignment", {}, { checkAction: false, checkPossibleActions: true });
                }
            );
        }
    }

    /**
     * Render assignment cards for other players in the pending area.
     * Shows revealed assignments as faceup cards, hidden ones as facedown.
     */
    private _renderOtherPlayersAssignments() {
        const gamedatas = this.bga.gameui.gamedatas as TrickerionGamedatas;
        const assignedOther = gamedatas.assignments.assigned.other;
        const currentPlayerId = this.bga.players.getCurrentPlayerId();

        const pending = document.getElementById('trickerion-pending');
        if (!pending) return;

        pending.innerHTML = '<div class="start-assignment-title">' + _('Other players are assigning characters') + '</div>';

        for (const playerIdStr of Object.keys(assignedOther)) {
            const playerId = parseInt(playerIdStr, 10);
            if (playerId === currentPlayerId) continue;

            const player = gamedatas.players[playerId];
            const otherAssignments = assignedOther[playerId];
            if (!otherAssignments) continue;

            // Player name header
            const playerColor = player?.color ?? '#333';
            pending.insertAdjacentHTML('beforeend', `
                <div class="other-player-assignments" data-player-id="${playerId}">
                    <div class="other-player-header" style="border-color: #${playerColor}; color: #${playerColor}">
                        ${player?.name ?? _('Player') + ' ' + playerId}
                    </div>
                    <div class="other-player-cards"></div>
                </div>
            `);

            const cardsContainer = pending.querySelector(`.other-player-assignments[data-player-id="${playerId}"] .other-player-cards`) as HTMLElement;

            // Revealed assignments
            for (const assignment of otherAssignments.revealed) {
                cardsContainer.insertAdjacentHTML('beforeend', cards.tplAssignmentCard(assignment));
            }

            // Hidden assignments (facedown — show count)
            for (const hidden of otherAssignments.hidden) {
                cardsContainer.insertAdjacentHTML('beforeend', `
                    <div class="assignment-card assignment-card-hidden">
                        <div class="assignment-card-inner">
                            <div class="card-back">${_(hidden.location)}</div>
                        </div>
                    </div>
                `);
            }
        }

        attachRegisteredTooltips();
    }

    private _cleanup() {
        const pending = document.getElementById('trickerion-pending');
        if (pending) {
            const title = pending.querySelector('.start-assignment-title');
            if (title) title.remove();
            pending.querySelectorAll('.other-player-assignments').forEach((el) => el.remove());
        }
    }
}
