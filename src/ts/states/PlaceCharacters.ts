import { Game } from '../Game';

export class PlaceCharacters {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(_args: object, _isCurrentPlayerActive: boolean) {}

    onLeavingState(_args: object, _isCurrentPlayerActive: boolean) {}

    onPlayerActivationChange(_args: object, _isCurrentPlayerActive: boolean) {}

    /**
     * Fires from PlaceCharacters.onEnteringState() after ResolveAssignments.
     * All facedown assignment cards become faceup — just add .flipped to the
     * flip container to trigger the CSS 3D rotation.
     */
    async notif_assignmentsRevealed(args: AssignmentsRevealedArgs) {
        for (const assignment of args.assignments) {
            // Query the flip container by id
            const flipContainer = $(`flip-assignment-card-${assignment.id}`);
            if (flipContainer) {
                flipContainer.classList.add('flipped');
            }
        }
    }
}
