/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * Trickerion implementation : © Timothée Pecatte <tim.pecatte@gmail.com>, Jurica Hladek <jurica.hladek@gmail.com>
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

import { ConfirmTurn } from './framework/states/ConfirmTurn.js';
import { StateProcessor } from './framework/StateProcessor.js';
import { clearPersistantActionButtonsNode, clearRestartActionButtonsNode, debug, initUtils } from './framework/utils.js';
import { ResolveChoice } from './framework/states/ResolveChoice.js';
import { overrideGamePrototype } from './framework/overrideGamePrototype.js';
import { DummyEnd } from './states/DummyEnd.js';
import { ChooseMagician } from './states/ChooseMagician.js';
import { LearnTrick } from './states/LearnTrick.js';
import { PickComponents } from './states/PickComponents.js';
import { HireCharacter } from './states/HireCharacter.js';
import { PrepareTrick } from './states/PrepareTrick.js';
import { Advertise } from './states/Advertise.js';
import { AssignCharacters } from './states/AssignCharacters.js';
import { PlaceCharacter } from './states/PlaceCharacter.js';
import { StartAssignment } from './states/StartAssignment.js';
import { Performance } from './states/Performance.js';
import { PlayLocationAction } from './states/PlayLocationAction.js';
import { DrawAssignmentCards } from './states/DrawAssignmentCards.js';
import { MakeDieUnavailable } from './states/MakeDieUnavailable.js';
import { TakeCoins } from './states/TakeCoins.js';
import { RerollDie } from './states/RerollDie.js';
import { SetDie } from './states/SetDie.js';
import { BuyComponents } from './states/BuyComponents.js';
import { OrderComponent } from './states/OrderComponent.js';
import { QuickOrderComponent } from './states/QuickOrderComponent.js';
import { AnytimeActions } from './states/AnytimeActions.js';
import { DiscardComponents } from './states/DiscardComponents.js';
import { DiscardTrick } from './states/DiscardTrick.js';
import { MoveComponents } from './states/MoveComponents.js';
import { MoveTrick } from './states/MoveTrick.js';
import { MoveApprentice } from './states/MoveApprentice.js';
import { board } from './Board.js';
import { SetupTrick } from './states/SetupTrick.js';
import { Reschedule } from './states/Reschedule.js';

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
        this.bga.states.register('PlaceCharacter', new PlaceCharacter(this, bga));
        this.bga.states.register('AssignCharacters', new AssignCharacters(this, bga));
        this.bga.states.register('StartAssignment', new StartAssignment(this, bga));
        this.bga.states.register('Performance', new Performance(this, bga));
        this.bga.states.register('PlayLocationAction', new PlayLocationAction(this, bga));
        this.bga.states.register('DrawAssignmentCards', new DrawAssignmentCards(this, bga));
        this.bga.states.register('MakeDieUnavailable', new MakeDieUnavailable(this, bga));
        this.bga.states.register('TakeCoins', new TakeCoins(this, bga));
        this.bga.states.register('RerollDie', new RerollDie(this, bga));
        this.bga.states.register('SetDie', new SetDie(this, bga));
        this.bga.states.register('BuyComponents', new BuyComponents(this, bga));
        this.bga.states.register('OrderComponent', new OrderComponent(this, bga));
        this.bga.states.register('QuickOrderComponent', new QuickOrderComponent(this, bga));
        this.bga.states.register('client_selectAnytimeAction', new AnytimeActions(this, bga));
        this.bga.states.register('DiscardComponents', new DiscardComponents(this, bga));
        this.bga.states.register('DiscardTrick', new DiscardTrick(this, bga));
        this.bga.states.register('MoveTrick', new MoveTrick(this, bga));
        this.bga.states.register('MoveComponents', new MoveComponents(this, bga));
        this.bga.states.register('MoveApprentice', new MoveApprentice(this, bga));
        this.bga.states.register('SetupTrick', new SetupTrick(this, bga));
        this.bga.states.register('Reschedule', new Reschedule(this, bga));

        this.stateProcessor = new StateProcessor(this, bga);
        initUtils(this.bga.gameui);
    }

    setup(gamedatas) {
        debug('Setup', gamedatas);
        this.gamedatas = gamedatas;
        this.render(gamedatas);
        this.setupNotifications();
        overrideGamePrototype(this.bga.gameui);
        board.init(gamedatas);
    }

    render(gamedatas) {}

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
        console.log('notifications subscriptions setup');

        this.bga.notifications.setupPromiseNotifications({
            prefix: 'notif_',
            handlers: [this, ...this.bga.states.getStateClasses()],
            onStart: (notifName, msg, args) => {
                $('pagemaintitletext').innerHTML = msg;
                $('gameaction_status').innerHTML = msg;
            },
            onEnd: (notifName, msg, args) => {
                $('pagemaintitletext').innerHTML = '';
                $('gameaction_status').innerHTML = '';
            },
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
}
