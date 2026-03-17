import { staticData } from "../staticData.js";

export class BuyComponents {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args, isCurrentPlayerActive) {
        if (isCurrentPlayerActive) {
            console.dir(args);
            for (const component in args.availableComponents) {
                const label = `${component} (${staticData.components[component].cost} coins)`;

                const button = this.bga.statusBar.addActionButton(label, () => {
                    this.bga.statusBar.removeActionButtons();

                    for(const location in args.availableComponents[component]) {
                        this.bga.statusBar.addActionButton(`Place at ${location}`, () => {
                            this.bga.statusBar.removeActionButtons();
                            for (let i=1; i<=args.availableComponents[component][location]; i++) {
                                this.bga.statusBar.addActionButton(`Buy ${i}`, () => {
                                    this.bga.statusBar.removeActionButtons();
                                    const cost = staticData.components[component].cost * i;
                                    this.bga.statusBar.addActionButton(`Buy for ${cost} coins`, () => {
                                        this.bga.actions.performAction("actBuyComponents", { component, locationId: location, count: i, bargain: 0 });
                                    });

                                    if (args.remainingActionPoints > 0) {
                                        this.bga.statusBar.addActionButton(`Bargain`, () => {
                                            this.bga.statusBar.removeActionButtons();
                                            const maxBargain = Math.min(args.remainingActionPoints, cost);

                                            for (let bargain=1; bargain<=maxBargain; bargain++) {
                                                this.bga.statusBar.addActionButton(`Bargain for ${bargain} coins`, () => {
                                                    this.bga.actions.performAction("actBuyComponents", { component, locationId: location, count: i, bargain });
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