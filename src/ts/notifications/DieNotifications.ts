import { dice } from '../Dice';
import { bga } from '../framework/utils';

export class DieNotifications {
    constructor() {}

    async notif_diceRolled(args: DiceRolledArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.dice;
        dice.updateDice(gamedatas);
    }

    async notif_dieMadeUnavailable(args: DiceMadeUnavailableArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        
        const slotKey = dice.getSlotKey(args.dieType, args.dieId);
        await dice.rollDie(slotKey, 'not-available', 'turn');

        // Update the gamedatas after animation completes
        gamedatas.globals.dice = { ...gamedatas.globals.dice };
    }

    async notif_dieRerolled(args: DiceRerolledArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.newDice;
        
        // Animate the specific die that was rerolled
        if (args.dieType && args.dieId !== undefined) {
            const slotKey = dice.getSlotKey(args.dieType, args.dieId);
            if (slotKey) {
                // Animate to the new face value
                await dice.rollDie(slotKey, args.newDieFace);
                return;
            }
        }
        
        // Fallback: update all dice
        dice.updateDice(gamedatas);
    }

    async notif_dieSet(args: DiceSetArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.newDice;
        
        // Animate the specific die that was set
        if (args.dieType && args.dieId !== undefined) {
            const slotKey = dice.getSlotKey(args.dieType, args.dieId);
            if (slotKey) {
                // Animate to the new face value
                await dice.rollDie(slotKey, args.newDieFace);
                return;
            }
        }
        
        // Fallback: update all dice
        dice.updateDice(gamedatas);
    }
}
