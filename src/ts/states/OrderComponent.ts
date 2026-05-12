import { Game } from "../Game";

export class OrderComponent {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            console.dir(args);
            for (const component of args.availableComponents) {
                const button = this.bga.statusBar.addActionButton(component, () => {
                    this.bga.statusBar.removeActionButtons();

                    for(const slot of args.availableOrderSlots) {
                        this.bga.statusBar.addActionButton(`Place at ${slot}`, () => {
                            this.bga.actions.performAction("actOrderComponents", { component, slotId: slot });
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
    onLeavingState(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
    }
}