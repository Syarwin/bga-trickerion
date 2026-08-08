import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';
import { staticData } from '../staticData';

export class MoveTrick {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: MoveTrickArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: MoveTrickArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: MoveTrickArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: MoveTrickArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        if (!args.availableTricks.length) {
            this.bga.statusBar.addActionButton(
                _('No tricks to move'),
                () => {},
                { disabled: true }
            );
            return;
        }

        const playerId = getCurrentPlayerId();
        const availableIds = new Set(args.availableTricks.map((t) => t.id));

        // Phase 1: Highlight trick cards on the player board that can be moved
        let highlighted = false;
        for (const trick of args.availableTricks) {
            const el = $(`trick-${trick.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actMoveTrick', { trickId: trick.id });
            });
        }

        // Also highlight any trick card already on the engineer board (the swap target)
        // to give visual context — clicking it is not needed since the action resolves
        // by selecting the source trick. But we highlight the engineer slot container.
        const engineerTrickSlot = document.querySelector(
            `#magician-board-${playerId} .engineer-workshop .trick-slot`
        );
        if (engineerTrickSlot) {
            engineerTrickSlot.classList.add('target-highlight');
        }

        // Fallback: action buttons if trick cards aren't in the DOM
        if (!highlighted) {
            for (const trick of args.availableTricks) {
                const cat = trick.category ?? (staticData.tricks[trick.type] as any)?.category;
                const icon = cat ? formatIcon(cat) : '';
                const name = (staticData.tricks[trick.type] as any)?.name ?? trick.type;
                const label = `${icon} ${_(name)}`;

                this.bga.statusBar.addActionButton(
                    label,
                    () => {
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actMoveTrick', { trickId: trick.id });
                    }
                );
            }
        }
    }

    private _cleanup() {
        // Remove highlights from trick cards
        if (this._currentArgs) {
            for (const trick of this._currentArgs.availableTricks) {
                const el = $(`trick-${trick.id}`);
                if (el) {
                    el.classList.remove('selectable');
                }
            }
        }
        // Remove engineer slot highlight
        document.querySelectorAll('.engineer-workshop .trick-slot').forEach((el) => {
            el.classList.remove('target-highlight');
        });
    }
}
