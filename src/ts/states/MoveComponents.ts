import { Game } from "../Game";

export class MoveComponents {
    game: Game;
    bga: ExtendedBga;
    
    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * This method is called each time we are entering the game state. You can use this method to perform some user interface changes at this moment.
     */
    onEnteringState(args: MoveComponentsArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            for (const component of args.availableComponents) {
                this.bga.statusBar.addActionButton(component.type, () => {
                    if (args.usedSlots.length < 2) {
                        this.bga.actions.performAction("actMoveComponent", { componentId: component.id, toReplaceComponentId: null });
                        return;
                    }

                    this.bga.statusBar.removeActionButtons();
                    for (const toReplaceComponent of args.usedSlots) {
                        this.bga.statusBar.addActionButton(toReplaceComponent.type, () => this.bga.actions.performAction("actMoveComponent", { componentId: component.id, toReplaceComponentId: toReplaceComponent.id }));
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
    onLeavingState(args: MoveComponentsArgs, isCurrentPlayerActive: boolean) {
    }

    /**
     * This method is called each time the current player becomes active or inactive in a MULTIPLE_ACTIVE_PLAYER state. You can use this method to perform some user interface changes at this moment.
     * on MULTIPLE_ACTIVE_PLAYER states, you may want to call this function in onEnteringState using `this.onPlayerActivationChange(args, isCurrentPlayerActive)` at the end of onEnteringState.
     * If your state is not a MULTIPLE_ACTIVE_PLAYER one, you can delete this function.
     */
    onPlayerActivationChange(args: MoveComponentsArgs, isCurrentPlayerActive: boolean) {
    }
}