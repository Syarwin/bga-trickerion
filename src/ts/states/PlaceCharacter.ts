import { Game } from "../Game";

export class PlaceCharacter {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: PlaceCharacterArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const assignment of args.availableAssignments) {
                this.bga.statusBar.addActionButton(assignment.character.type, () => {
                    this.bga.statusBar.removeActionButtons();
                    for (const location of assignment.possibleLocations) {
                        this.bga.statusBar.addActionButton(location, () => {
                            this.bga.actions.performAction("actPlace", { characterId: assignment.character.id, locationId: location });
                        });
                    }
                    this.bga.statusBar.addActionButton("Leave idle", () => {
                        this.bga.actions.performAction("actLeaveIdle", { characterId: assignment.character.id });
                    }, {color: "alert"});
                    this.bga.statusBar.addActionButton("Cancel", () => {
                        this.bga.statusBar.removeActionButtons();
                        this.onEnteringState(args, isCurrentPlayerActive);
                    });
                });
            }
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: PlaceCharacterArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: PlaceCharacterArgs, isCurrentPlayerActive: boolean) {
    }

    async notif_characterIdled(args: CharacterIdledArgs) {
    }
}