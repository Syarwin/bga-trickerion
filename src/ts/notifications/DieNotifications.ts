import { dice } from '../Dice';
import { bga } from '../framework/utils';

export class DieNotifications {
    constructor() {}

    async notif_diceRolled(args: DiceRolledArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.dice;
        dice.updateDice(gamedatas);
    }

    async notif_dieMadeUnavailable(_args: DiceMadeUnavailableArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        dice.updateDice(gamedatas);
    }

    async notif_dieRerolled(args: DiceRerolledArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.newDice;
        dice.updateDice(gamedatas);
    }

    async notif_dieSet(args: DiceSetArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.dice = args.newDice;
        dice.updateDice(gamedatas);
    }
}
