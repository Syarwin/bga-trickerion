import { State } from '../framework/State';
import { onClick } from '../framework/event';
import { dice } from '../Dice';
import { formatIcon } from '../format';

/** Map character die slot key → DOM element id */
const CHAR_DIE_SLOTS: Record<string, string> = {
  'character-0': 'die-character-0',
  'character-1': 'die-character-1',
};

export class HireCharacter extends State {
  onEnteringState(args: HireCharacterArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

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
        this.actionOnClick(domId, 'actHireCharacter', { characterType: faceValue });
      }
    }

    // Fallback: action buttons for each available character type
    for (const charType of args.availableCharacterTypes) {
      this.addActionBtn(formatIcon(charType) + ' ' + _(charType), 'actHireCharacter', { characterType: charType });
    }
  }
}
