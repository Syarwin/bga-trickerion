import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { dice } from '../Dice';

/**
 * MakeDieUnavailable visual state.
 *
 * The backend wants the player to pick a die face to turn to "X".
 * We match available die faces to the physical dice displayed on the
 * downtown board and make them clickable.
 */
export class MakeDieUnavailable {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: MakeDieUnavailableArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: MakeDieUnavailableArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: MakeDieUnavailableArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: MakeDieUnavailableArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        if (!args.availableDice.length) {
            this.bga.statusBar.addActionButton(
                _('No dice available'),
                () => {},
                { disabled: true }
            );
            return;
        }

        // Map die face values to DOM die die elements
        const diceData = this.game.gamedatas?.globals?.dice;
        if (!diceData) {
            this._showButtons(args);
            return;
        }

        const availableSet = new Set(args.availableDice.map(String));
        let highlighted = false;

        const slotMap: Record<string, string> = {
            'character-0': 'die-character-0',
            'character-1': 'die-character-1',
            'trick-0': 'die-trick-0',
            'trick-1': 'die-trick-1',
            'money-0': 'die-bank-0',
            'money-1': 'die-bank-1',
        };

        const typeGroups: Record<string, { slotKey: string; face: string | number }[]> = {
            character: [
                { slotKey: 'character-0', face: diceData.character[0] },
                { slotKey: 'character-1', face: diceData.character[1] },
            ],
            trick: [
                { slotKey: 'trick-0', face: diceData.trick[0] },
                { slotKey: 'trick-1', face: diceData.trick[1] },
            ],
            money: [
                { slotKey: 'money-0', face: diceData.money[0] },
                { slotKey: 'money-1', face: diceData.money[1] },
            ],
        };

        for (const group of Object.values(typeGroups)) {
            for (const die of group) {
                if (!availableSet.has(String(die.face))) continue;
                if (die.face === 'not-available') continue;

                const domId = slotMap[die.slotKey];
                const el = $(domId);
                if (!el) continue;

                el.classList.add('selectable');
                highlighted = true;

                onClick(el, () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actMakeDieUnavailable', { dieFace: String(die.face) });
                });
            }
        }

        // Fallback: action buttons if no dice DOM found
        if (!highlighted) {
            this._showButtons(args);
        }
    }

    private _showButtons(args: MakeDieUnavailableArgs) {
        for (const die of args.availableDice) {
            const label = String(die);
            this.bga.statusBar.addActionButton(
                _(label),
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actMakeDieUnavailable', { dieFace: String(die) });
                }
            );
        }
    }

    private _cleanup() {
        const dieDomIds = [
            'die-character-0', 'die-character-1',
            'die-trick-0', 'die-trick-1',
            'die-bank-0', 'die-bank-1',
        ];
        for (const id of dieDomIds) {
            const el = $(id);
            if (el) el.classList.remove('selectable');
        }
    }
}
