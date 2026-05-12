import { Game } from "../Game";

export class ChooseMagician {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: ChooseMagicianArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const magician of args.availableMagicians) {
                this.bga.statusBar.addActionButton(magician.type, () => this.bga.actions.performAction("actChooseMagician", { magicianId: magician.id }));
            }
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: ChooseMagicianArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: ChooseMagicianArgs, isCurrentPlayerActive: boolean) {
    }

    async notif_magicianChosen(args: MagicianChosenArgs) {
    }
}