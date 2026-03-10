export class AssignCharacters {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args, isCurrentPlayerActive) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive)
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args, isCurrentPlayerActive) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args, isCurrentPlayerActive) {
        if (isCurrentPlayerActive) {
            for (const assignment of args.availableAssignments) {
                this.bga.statusBar.addActionButton(assignment.type, () => {
                    this.bga.statusBar.removeActionButtons();
                    for (const character of args.availableCharacters) {
                        this.bga.statusBar.addActionButton(character.type, () => {
                            this.bga.actions.performAction("actAssignCharacter", { assignmentId: assignment.id, characterId: character.id });
                        });
                    }
                    this.bga.statusBar.addActionButton("Cancel", () => {
                        this.bga.statusBar.removeActionButtons();
                        this.onEnteringState(args, isCurrentPlayerActive);
                    });
                });
            }

            this.bga.statusBar.addActionButton("Done", () => {
                this.bga.actions.performAction("actDone", {});
            });
            
            this.bga.statusBar.addActionButton("Reset", () => {
                this.bga.actions.performAction("actReset", {});
            });
        }
    }
}