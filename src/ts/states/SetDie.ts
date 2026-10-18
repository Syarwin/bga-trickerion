import { State } from '../framework/State';
import { onClick } from '../framework/event';
import { getFaceLabel } from '../Dice';

/**
 * Map die type + index → DOM element id.
 */
const DIE_SLOT_MAP: Record<string, Record<number, string>> = {
  character: { 0: 'die-character-0', 1: 'die-character-1' },
  trick: { 0: 'die-trick-0', 1: 'die-trick-1' },
  money: { 0: 'die-bank-0', 1: 'die-bank-1' },
};

export class SetDie extends State {
  onEnteringState(args: SetDieArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    const rolledDice = args.availableDice;

    for (const [dieType, faces] of Object.entries(rolledDice)) {
      for (let dieId = 0; dieId < faces.length; dieId++) {
        const domId = DIE_SLOT_MAP[dieType]?.[dieId];
        if (!domId) continue;

        onClick(domId, () => {
          // Transition to client state for Phase 2
          this.bga.states.setClientState('client_setDieFace', {
            descriptionmyturn: _('${you} must select a face for ${dieType} die'),
            args: {
              ...args,
              selectedDieType: dieType,
              selectedDieId: dieId,
              selectedDomId: domId,
              dieType,
            },
          });
        });
      }
    }
  }
}

// Client state for Phase 2: Face selection
export class ClientSetDieFace extends State {
  onEnteringState(args: ClientSetDieFaceArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    const dieType = args.selectedDieType;
    const dieId = args.selectedDieId;
    const faces = args.availableFaces[dieType]?.[dieId];

    if (!faces || faces.length === 0) {
      this.bga.states.restoreServerGameState();
      return;
    }

    for (const dieFace of faces) {
      const label = getFaceLabel(dieFace, dieType);
      this.addActionBtn(label, 'actSetDie', { dieType, dieId, dieFace: String(dieFace) });
    }

    // Cancel: go back to Phase 1
    this.addCancelStateBtn();
  }
}

interface ClientSetDieFaceArgs extends SetDieArgs {
  selectedDieType: string;
  selectedDieId: number;
  selectedDomId: string;
}
