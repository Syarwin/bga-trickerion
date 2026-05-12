import { irreversibleAction } from "../framework/engine.js";
import { Game } from "../Game.js";

export class RerollDie {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const dieType in args.availableDice) {

                for (let i = 0; i < args.availableDice[dieType].length; i++) {
                    const dieFace = args.availableDice[dieType][i];
                    this.bga.statusBar.addActionButton(`${dieType}: ${dieFace}`, irreversibleAction(() => this.bga.actions.performAction("actRerollDie", { dieType, dieId: i })));
                }
            }
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
    }
}