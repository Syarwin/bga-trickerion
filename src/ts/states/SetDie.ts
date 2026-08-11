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

/** Reverse map DOM id → slotKey for dice.rollDie() */
const DOM_ID_TO_SLOT: Record<string, string> = {
    'die-character-0': 'character-0',
    'die-character-1': 'character-1',
    'die-trick-0': 'trick-0',
    'die-trick-1': 'trick-1',
    'die-bank-0': 'money-0',
    'die-bank-1': 'money-1',
};

export class SetDie {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: SetDieArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: SetDieArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: SetDieArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        // Phase 1: Show available dice on the board
        this._showDiceSelection(args);
    }

    /**
     * Phase 1: Make each available die clickable on the board.
     */
    private _showDiceSelection(args: SetDieArgs) {
        const rolledDice = args.availableDice;

        for (const [dieType, faces] of Object.entries(rolledDice)) {
            for (let dieId = 0; dieId < faces.length; dieId++) {
                const face = faces[dieId];
                if (face === 'not-available') continue;

                const domId = DIE_SLOT_MAP[dieType]?.[dieId];
                if (!domId) continue;

                const el = $(domId);
                if (!el) continue;

                el.classList.add('selectable');
                onClick(el, () => {
                    clearPossible();
                    this._showFaceSelection(args, dieType, dieId, domId);
                });
            }
        }
    }

    /**
     * Phase 2: Show the possible faces for the selected die + Cancel.
     */
    private _showFaceSelection(args: SetDieArgs, dieType: string, dieId: number, domId: string) {
        this.bga.statusBar.removeActionButtons();

        const faces = args.availableFaces[dieType]?.[dieId];
        if (!faces || faces.length === 0) return;

        for (const dieFace of faces) {
            const label = this._faceLabel(dieFace, dieType);
            this.bga.statusBar.addActionButton(
                label,
                irreversibleAction(() => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actSetDie', { dieType, dieId, dieFace: String(dieFace) });
                })
            );
        }

        // Cancel: go back to Phase 1
        this.bga.statusBar.addActionButton(
            _('Cancel'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this._showDiceSelection(args);
            },
            { color: 'alert' }
        );
    }

    /**
     * Build a human-readable label for a die face.
     */
    private _faceLabel(face: string | number, dieType: string): string {
        if (face === 'not-available') return 'X';
        if (face === 'any') return '?';

        const labels: Record<string, Record<string | number, string>> = {
            character: {
                apprentice: _('Apprentice'),
                assistant: _('Assistant'),
                manager: _('Manager'),
                engineer: _('Engineer'),
            },
            trick: {
                escape: _('Escape'),
                mechanical: _('Mechanical'),
                optical: _('Optical'),
                spiritual: _('Spiritual'),
                any: '?',
            },
            money: {},
        };

        if (typeof face === 'string' && labels[dieType]?.[face]) {
            return labels[dieType][face];
        }

        return `${face} ${dieType === 'money' ? 'coins' : ''}`.trim();
    }
}
