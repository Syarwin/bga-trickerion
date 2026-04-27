import { ConfirmTurn } from "./framework/states/ConfirmTurn";
import { StateProcessor } from "./framework/StateProcessor";
import { clearPersistantActionButtonsNode, clearRestartActionButtonsNode, debug, initUtils } from "./framework/utils";
import { ResolveChoice } from "./framework/states/ResolveChoice";
import { overrideGamePrototype } from "./framework/overrideGamePrototype";
import { DummyEnd } from "./framework/states/DummyEnd";
import { showEngine } from "./framework/engine";
import { AnytimeActions } from "./framework/states/AnytimeActions";
import { ChooseMagician } from "./states/ChooseMagician";
import { LearnTrick } from "./states/LearnTrick";
import { PickComponents } from "./states/PickComponents";
import { HireCharacter } from "./states/HireCharacter";
import { PrepareTrick } from "./states/PrepareTrick";
import { Advertise } from "./states/Advertise";
import { PlaceCharacter } from "./states/PlaceCharacter";
import { AssignCharacters } from "./states/AssignCharacters";
import { StartAssignment } from "./states/StartAssignment";
import { PlayLocationAction } from "./states/PlayLocationAction";
import { DrawAssignmentCards } from "./states/DrawAssignmentCards";
import { MakeDieUnavailable } from "./states/MakeDieUnavailable";
import { TakeCoins } from "./states/TakeCoins";
import { RerollDie } from "./states/RerollDie";
import { SetDie } from "./states/SetDie";
import { BuyComponents } from "./states/BuyComponents";
import { OrderComponent } from "./states/OrderComponent";
import { QuickOrderComponent } from "./states/QuickOrderComponent";
import { DiscardComponents } from "./states/DiscardComponents";
import { DiscardTrick } from "./states/DiscardTrick";
import { MoveTrick } from "./states/MoveTrick";
import { MoveComponents } from "./states/MoveComponents";
import { MoveApprentice } from "./states/MoveApprentice";
import { SetupTrick } from "./states/SetupTrick";
import { Reschedule } from "./states/Reschedule";
import { Performance } from "./states/Performance";
import { board } from "./Board";
import notifications from "./notifications";
import { EnhanceCharacter } from "./states/EnhanceCharacter";
import { FortuneTelling } from "./states/FortuneTelling";
import { FinishSetup } from "./states/FinishSetup";
import { PlaceCharacters } from "./states/PlaceCharacters";

export class Game {
    bga: ExtendedBga;
    stateProcessor: StateProcessor;
    gamedatas: TrickerionGamedatas;

    constructor(bga: ExtendedBga) {
        this.bga = bga;
        
        // Declare the State classes
        this.bga.states.register('ConfirmTurn', new ConfirmTurn(this, bga));
        this.bga.states.register('ConfirmPartialTurn', new ConfirmTurn(this, bga));
        this.bga.states.register('ResolveChoice', new ResolveChoice(this, bga));
        this.bga.states.register('client_selectAnytimeAction', new AnytimeActions(this, bga));
        this.bga.states.register('DummyEnd', new DummyEnd(this, bga));

        this.bga.states.register('ChooseMagician', new ChooseMagician(this, bga));
        this.bga.states.register('EnhanceCharacter', new EnhanceCharacter(this, bga));
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
        this.bga.states.register('DiscardComponents', new DiscardComponents(this, bga));
        this.bga.states.register('DiscardTrick', new DiscardTrick(this, bga));
        this.bga.states.register('MoveTrick', new MoveTrick(this, bga));
        this.bga.states.register('MoveComponents', new MoveComponents(this, bga));
        this.bga.states.register('MoveApprentice', new MoveApprentice(this, bga));
        this.bga.states.register('SetupTrick', new SetupTrick(this, bga));
        this.bga.states.register('Reschedule', new Reschedule(this, bga));
        this.bga.states.register('FortuneTelling', new FortuneTelling(this, bga));
        this.bga.states.register('FinishSetup', new FinishSetup(this, bga));
        this.bga.states.register('PlaceCharacters', new PlaceCharacters(this, bga));

        this.stateProcessor = new StateProcessor(this, bga);
        initUtils(this.bga);        
    }
    
    setup( gamedatas: TrickerionGamedatas ) {
        debug('Setup', gamedatas);
        this.gamedatas = gamedatas;
        this.render(gamedatas);
        this.setupNotifications();
        overrideGamePrototype(this.bga.gameui);
        board.init(gamedatas);
    }

    render(gamedatas: TrickerionGamedatas) {
    }

    onEnteringState(stateName: string, args: Gamestate) {
        this.stateProcessor.process(args.args, args);
    }

    onLeavingState(stateName: string, args: Gamestate) {
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
                your fivepairs.game.php file.
    
    */
    setupNotifications() {
        console.log( 'notifications subscriptions setup' );
        
        // automatically listen to the notifications, based on the `notif_xxx` function on this class. 
        // Uncomment the logger param to see debug information in the console about notifications.
        this.bga.notifications.setupPromiseNotifications({
            handlers: [this, ...this.bga.states.getStateClasses(), ...notifications],
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

    async notif_refreshUI(args: RefreshUIArgs) {
        this.bga.gameArea.getElement().innerHTML = '';

        for (const playerId in args.data.players) {
            this.bga.playerPanels.getElement(+playerId).innerHTML = '';
        }

        this.render(args.data as TrickerionGamedatas);
    }

    async notif_clearTurn(args: ClearTurnArgs) {
        this.bga.gameui.cancelLogs(args.notifIds);
    }

    async notif_engineShown(args: EngineShownArgs) {
        showEngine(args.engine);
    }
    
}
