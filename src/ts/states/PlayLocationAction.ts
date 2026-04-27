import { Game } from "../Game";

export class PlayLocationAction {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const actionId in args.availableActions) {
                const action = args.availableActions[actionId];
                const cost = action.actionPoints;
                const minCost = action.minActionPoints;

                let costLabel = cost ? `${cost} AP` : ( minCost ? `min ${minCost} AP` : null);
                costLabel = costLabel ? ` (${costLabel})` : "";

                const actionLabel = `${actionId}${costLabel}`;

                this.bga.statusBar.addActionButton(actionLabel, () => this.bga.actions.performAction("actPlayAction", { actionId: actionId }));
            }
        }
    }

    /**
     * This method is called each time we are leaving the game state. You can use this method to perform some user interface changes at this moment.
     */
    onLeavingState(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
    }
}