import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { dice } from '../Dice';

/** Map bank die slot key → DOM element id */
const BANK_DIE_SLOTS: Record<string, string> = {
    'money-0': 'die-bank-0',
    'money-1': 'die-bank-1',
};

export class TakeCoins {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: TakeCoinsArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: TakeCoinsArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: TakeCoinsArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        // Map available coin amounts to the bank dice currently showing those faces
        const moneyDice = this.game.gamedatas?.globals?.dice?.money ?? [];
        const availableSet = new Set(args.availableCoins);

        for (const slotKey of Object.keys(BANK_DIE_SLOTS)) {
            const idx = slotKey === 'money-0' ? 0 : 1;
            const faceValue = moneyDice[idx];

            // Only make clickable if the die is available (not 'X') and the
            // value is one of the available coin amounts
            if (faceValue === 'not-available') continue;

            const amount = Number(faceValue);
            if (isNaN(amount) || !availableSet.has(amount)) continue;

            const domId = BANK_DIE_SLOTS[slotKey];
            const el = $(domId);
            if (!el) continue;

            el.classList.add('selectable');

            onClick(el, () => {
                this.bga.actions.performAction('actTakeCoins', { coins: amount });

                // Roll the die to "X" for visual feedback
                dice.rollDie(slotKey, 'not-available');

                // Deselect everything
                clearPossible();
                this.bga.statusBar.removeActionButtons();
            });
        }

        // Fallback: add action buttons for each available coin amount
        for (const coins of args.availableCoins) {
            this.bga.statusBar.addActionButton(
                _('Get ${coins} coins').replace('${coins}', String(coins)),
                () => {
                    clearPossible();
                    this.bga.actions.performAction('actTakeCoins', { coins });

                    // Also roll the matching die to "X" if found
                    for (const [slotKey, domId] of Object.entries(BANK_DIE_SLOTS)) {
                        const idx = slotKey === 'money-0' ? 0 : 1;
                        const faceValue = moneyDice[idx];
                        if (Number(faceValue) === coins) {
                            dice.rollDie(slotKey, 'not-available');
                            break;
                        }
                    }

                    this.bga.statusBar.removeActionButtons();
                }
            );
        }
    }
}
