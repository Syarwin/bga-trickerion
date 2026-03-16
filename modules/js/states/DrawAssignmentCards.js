export class DrawAssignmentCards {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args, isCurrentPlayerActive) {
        if (isCurrentPlayerActive) {
            for (const locationId of args.availableLocations) {
                this.bga.statusBar.addActionButton(`${locationId} (-${args.currentDrawCost} AP)`, () => this.bga.actions.performAction("actDrawAssignmentCards", { deckLocationId: locationId }));
            }

            for (const card of args.drawnCards) {
                const button = this.bga.statusBar.addActionButton(card.type, () => {
                    button.classList.toggle("selected");
                }, {color: "gray"});
                button.dataset.cardId = card.id;
            }

            if (args.drawnCards.length > 0) {
                this.bga.statusBar.addActionButton("Discard", () => {
                    const selectedCardIds = Array.from(document.getElementById("generalactions").querySelectorAll(".bgabutton.selected")).map(button => button.dataset.cardId);
                    this.bga.actions.performAction("actDiscardCards", { cardIds: selectedCardIds });
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