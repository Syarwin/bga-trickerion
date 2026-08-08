import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { dice } from '../Dice';
import { formatIcon } from '../format';

/** Map character die slot key → DOM element id */
const CHAR_DIE_SLOTS: Record<string, string> = {
    'character-0': 'die-character-0',
    'character-1': 'die-character-1',
};

export class HireCharacter {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: HireCharacterArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: HireCharacterArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: HireCharacterArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        // Match available character types to character dice on the downtown board
        const charDice = this.game.gamedatas?.globals?.dice?.character ?? [];
        const availableSet = new Set(args.availableCharacterTypes);

        for (const slotKey of Object.keys(CHAR_DIE_SLOTS)) {
            const idx = slotKey === 'character-0' ? 0 : 1;
            const faceValue = charDice[idx];

            if (faceValue === 'not-available') continue;

            // The die face is a character type — check if it's available
            if (typeof faceValue === 'string' && availableSet.has(faceValue)) {
                const domId = CHAR_DIE_SLOTS[slotKey];
                const el = $(domId);
                if (!el) continue;

                el.classList.add('selectable');

                onClick(el, () => {
                    this.bga.actions.performAction('actHireCharacter', { characterType: faceValue });
                    dice.rollDie(slotKey, 'not-available');
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                });
            }
        }

        // Fallback: action buttons for each available character type
        for (const charType of args.availableCharacterTypes) {
            this.bga.statusBar.addActionButton(
                formatIcon(charType) + ' ' + _(charType),
                () => {
                    this.bga.actions.performAction('actHireCharacter', { characterType: charType });
                    // Roll the matching die to X
                    for (const [slotKey] of Object.entries(CHAR_DIE_SLOTS)) {
                        const idx = slotKey === 'character-0' ? 0 : 1;
                        if (charDice[idx] === charType) {
                            dice.rollDie(slotKey, 'not-available');
                            break;
                        }
                    }
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                }
            );
        }
    }
}
