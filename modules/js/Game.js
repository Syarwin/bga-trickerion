/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * Trickerion implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Game.js
 *
 * Trickerion user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

import { ConfirmTurn } from "./framework/states/ConfirmTurn.js";
import { StateProcessor } from "./framework/StateProcessor.js";
import { clearPersistantActionButtonsNode, clearRestartActionButtonsNode, initUtils } from "./framework/utils.js";
import { ResolveChoice } from "./framework/states/ResolveChoice.js";
import { overrideGamePrototype } from "./framework/overrideGamePrototype.js";
import { DummyEnd } from "./states/DummyEnd.js";
import { ChooseMagician } from "./states/ChooseMagician.js";
import { LearnTrick } from "./states/LearnTrick.js";
import { PickComponents } from "./states/PickComponents.js";
import { HireCharacter } from "./states/HireCharacter.js";
import { PrepareTrick } from "./states/PrepareTrick.js";
import { Advertise } from "./states/Advertise.js";

export class Game {
    constructor(bga) {
        this.bga = bga;
        
        // Declare the State classes
        this.bga.states.register('ConfirmTurn', new ConfirmTurn(this, bga));
        this.bga.states.register('ConfirmPartialTurn', new ConfirmTurn(this, bga));
        this.bga.states.register('ResolveChoice', new ResolveChoice(this, bga));
        this.bga.states.register('DummyEnd', new DummyEnd(this, bga));
        this.bga.states.register('ChooseMagician', new ChooseMagician(this, bga));
        this.bga.states.register('LearnTrick', new LearnTrick(this, bga));
        this.bga.states.register('PickComponents', new PickComponents(this, bga));
        this.bga.states.register('HireCharacter', new HireCharacter(this, bga));
        this.bga.states.register('PrepareTrick', new PrepareTrick(this, bga));
        this.bga.states.register('Advertise', new Advertise(this, bga));

        this.stateProcessor = new StateProcessor(this, bga);
        initUtils(this.bga.gameui);        
    }
    
    setup( gamedatas ) {
        this.gamedatas = gamedatas;
        this.render(gamedatas);
        this.setupNotifications();
        overrideGamePrototype(this.bga.gameui);
    }

    render(gamedatas) {
    }

    onEnteringState(stateName, args) {
        this.stateProcessor.process(args.args, args);
    }

    onLeavingState(stateName, args) {
        clearPersistantActionButtonsNode();
        clearRestartActionButtonsNode();
    }

    ///////////////////////////////////////////////////
    //// Utility methods
    
    /*
    
        Here, you can defines some utility methods that you can use everywhere in your javascript
        script. Typically, functions that are used in multiple state classes or outside a state class.
    
    */

    
    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications

    /*
        setupNotifications:
        
        In this method, you associate each of your game notifications with your local method to handle it.
        
        Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                your Trickerion.game.php file.
    
    */
    setupNotifications() {
        console.log( 'notifications subscriptions setup' );
        
        // automatically listen to the notifications, based on the `notif_xxx` function on this class. 
        // Uncomment the logger param to see debug information in the console about notifications.
        this.bga.notifications.setupPromiseNotifications({
            handlers: [this, ...this.bga.states.getStateClasses()], 
        });
    }

    async notif_refreshUI(args) {
        this.bga.gameArea.getElement().innerHTML = '';

        for (const playerId in args.data.players) {
            this.bga.playerPanels.getElement(playerId).innerHTML = '';
        }

        this.render(args.data);
    }

    async notif_clearTurn(args) {
        this.bga.gameui.cancelLogs(args.notifIds);
    }

    
    
    // TODO: from this point and below, you can write your game notifications handling methods
    
    /*
    Example:
    async notif_cardPlayed( args ) {
        // Note: args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
        
        // TODO: play the card in the user interface.
    }
    */
}
