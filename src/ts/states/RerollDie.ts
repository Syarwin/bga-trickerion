import { State } from '../framework/State';
import { onClick } from '../framework/event';
import { irreversibleAction } from '../framework/engine';

/**
 * Map die type + index → DOM element id.
 */
const DIE_SLOT_MAP: Record<string, Record<number, string>> = {
  character: { 0: 'die-character-0', 1: 'die-character-1' },
  trick: { 0: 'die-trick-0', 1: 'die-trick-1' },
  money: { 0: 'die-bank-0', 1: 'die-bank-1' },
};

export class RerollDie extends State {
  onEnteringState(args: RerollDieArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    const rolledDice = args.availableDice;

    // Make each available die on the board clickable
    for (const [dieType, faces] of Object.entries(rolledDice)) {
      for (let dieId = 0; dieId < faces.length; dieId++) {
        const domId = DIE_SLOT_MAP[dieType]?.[dieId];
        onClick(
          $(domId),
          irreversibleAction(() => {
            this.bga.actions.performAction('actRerollDie', { dieType, dieId });
          })
        );
      }
    }

    // TODO : please document to know when this might be used
    // Source label for context
    if (args.sourceName) {
      this.bga.statusBar.addActionButton(
        _('Cancel'),
        () => {
          this.bga.actions.performAction('actPass', {});
        },
        { color: 'alert' }
      );
    }
  }
}
