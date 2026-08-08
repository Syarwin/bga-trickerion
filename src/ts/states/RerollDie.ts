import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { dice } from '../Dice';
import { irreversibleAction } from '../framework/engine';

/**
 * Map die type + index → DOM element id.
 */
const DIE_SLOT_MAP: Record<string, Record<number, string>> = {
    character: { 0: 'die-character-0', 1: 'die-character-1' },
    trick: { 0: 'die-trick-0', 1: 'die-trick-1' },
    money: { 0: 'die-bank-0', 1: 'die-bank-1' },
};

/** Reverse map DOM id → (type, idx) so we can roll the animation */
const DOM_ID_TO_SLOT: Record<string, string> = {
    'die-character-0': 'character-0',
    'die-character-1': 'character-1',
    'die-trick-0': 'trick-0',
    'die-trick-1': 'trick-1',
    'die-bank-0': 'money-0',
    'die-bank-1': 'money-1',
};

export class RerollDie {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: RerollDieArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        const rolledDice = args.availableDice;

        // Make each available die on the board clickable
        for (const [dieType, faces] of Object.entries(rolledDice)) {
            for (let dieId = 0; dieId < faces.length; dieId++) {
                const face = faces[dieId];
                if (face === 'not-available') continue;

                const domId = DIE_SLOT_MAP[dieType]?.[dieId];
                if (!domId) continue;

                const el = $(domId);
                if (!el) continue;

                el.classList.add('selectable');
                onClick(el, irreversibleAction(() => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actRerollDie', { dieType, dieId });

                    // Animate die roll to not-available
                    const slotKey = DOM_ID_TO_SLOT[domId];
                    if (slotKey) {
                        dice.rollDie(slotKey, 'not-available');
                    }
                }));
            }
        }

        // Source label for context
        if (args.sourceName) {
            this.bga.statusBar.addActionButton(
                _('Cancel'),
                () => {
                    clearPossible();
                    this.bga.actions.performAction('actPass', {});
                },
                { color: 'alert' }
            );
        }
    }
}
