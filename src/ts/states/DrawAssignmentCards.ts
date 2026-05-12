import { irreversibleAction } from "../framework/engine.js";
import { Game } from "../Game.js";

export class DrawAssignmentCards {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: DrawAssignmentCardsArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const locationId of args.availableLocations) {
                this.bga.statusBar.addActionButton(`${locationId} (-${args.currentDrawCost} AP)`, irreversibleAction(() => this.bga.actions.performAction("actDrawAssignmentCards", { deckLocationId: locationId })));
            }

            for (const card of args.drawnCards) {
                const button: HTMLElement = this.bga.statusBar.addActionButton(card.type, () => {
                    button.classList.toggle("selected");
                }, {color: "secondary"});
                button.dataset.cardId = card.id.toString();
            }

            if (args.drawnCards.length > 0) {
                this.bga.statusBar.addActionButton("Discard", () => {
                    const selectedCardIds = Array.from(document.getElementById("generalactions").querySelectorAll(".bgabutton.selected")).map((button: HTMLElement) => button.dataset.cardId);
                    this.bga.actions.performAction("actDiscardCards", { cardIds: selectedCardIds });
                });
            }
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: DrawAssignmentCardsArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: DrawAssignmentCardsArgs, isCurrentPlayerActive: boolean) {
    }
}