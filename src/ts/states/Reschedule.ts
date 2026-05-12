import { Game } from "../Game";

export class Reschedule {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: RescheduleArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const trickMarker of args.availableTrickMarkers) {
                this.bga.statusBar.addActionButton(trickMarker.id.toString(), () => {
                    this.bga.statusBar.removeActionButtons();
                    for (const performanceId in args.possiblePerformances[trickMarker.id]) {
                        const performance = args.possiblePerformances[trickMarker.id][performanceId].performance;
                        this.bga.statusBar.addActionButton(performance.type, () => {
                            this.bga.statusBar.removeActionButtons();
                            const possibleSlots = args.possiblePerformances[trickMarker.id][performanceId].possibleSlots;
                            for (const slotId in possibleSlots) {
                                this.bga.statusBar.addActionButton(slotId, () => {
                                    this.bga.statusBar.removeActionButtons();
                                    for (const link of possibleSlots[slotId].links) {
                                        this.bga.statusBar.addActionButton(link.direction, () => {
                                            this.bga.actions.performAction("actRescheduleTrick", { trickMarkerId: trickMarker.id, slotId: slotId, performanceId: performance.id, direction: link.direction });
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
    onLeavingState(args: RescheduleArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: RescheduleArgs, isCurrentPlayerActive: boolean) {
    }
}