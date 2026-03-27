export class SetupTrick {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args, isCurrentPlayerActive) {
        if (isCurrentPlayerActive) {
            for (const performance of args.availablePerformances) {
                this.bga.statusBar.addActionButton(performance.performance.type, () => {
                    this.bga.statusBar.removeActionButtons();
                    for (const trick of performance.possibleTricks) {
                        this.bga.statusBar.addActionButton(trick.type, () => {
                            this.bga.statusBar.removeActionButtons();
                            for (const slotId in performance.possibleSlots) {
                                this.bga.statusBar.addActionButton(slotId, () => {
                                    this.bga.statusBar.removeActionButtons();
                                    for (const link of performance.possibleSlots[slotId].links) {
                                        this.bga.statusBar.addActionButton(link.direction, () => {
                                            this.bga.actions.performAction("actSetupTrick", { trickId: trick.id, slotId: slotId, performanceId: performance.performance.id, direction: link.direction });
                                        });
                                    }

                                    this.bga.statusBar.addActionButton("Cancel", () => {
                                        this.bga.statusBar.removeActionButtons();
                                        this.onEnteringState(args, isCurrentPlayerActive);
                                    });
                                });
                            }

                            this.bga.statusBar.addActionButton("Cancel", () => {
                                this.bga.statusBar.removeActionButtons();
                                this.onEnteringState(args, isCurrentPlayerActive);
                            });
                        });
                    }

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
    onLeavingState(args, isCurrentPlayerActive) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args, isCurrentPlayerActive) {
    }
}