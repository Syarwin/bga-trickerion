import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';
import { staticData } from '../staticData';

export class PrepareTrick {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: PrepareTrickArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: PrepareTrickArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: PrepareTrickArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: PrepareTrickArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        if (!args.availableTricks.length) {
            const label = args.sourceName
                ? _('No tricks available to prepare')
                : _('No tricks available to prepare');
            this.bga.statusBar.addActionButton(
                _(label),
                () => {},
                { disabled: true }
            );
            return;
        }

        // Phase 1: Highlight trick cards on the player board that can be prepared
        let highlighted = false;
        for (const trick of args.availableTricks) {
            const el = $(`trick-${trick.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actPrepareTrick', { trickId: trick.id });
            });
        }

        // Highlight the prepare action space on the workshop as visual context
        const playerId = getCurrentPlayerId();
        const prepareEl = $(`workshop-${playerId}-prepare`);
        if (prepareEl) {
            prepareEl.classList.add('target-highlight');
        }

        // Fallback: action buttons if trick cards aren't in DOM
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
                        this.bga.actions.performAction('actPrepareTrick', { trickId: trick.id });
                    }
                );
            }
        }
    }

    private _cleanup() {
        if (this._currentArgs) {
            for (const trick of this._currentArgs.availableTricks) {
                const el = $(`trick-${trick.id}`);
                if (el) el.classList.remove('selectable');
            }
        }
        document.querySelectorAll('[id$="-prepare"]').forEach((el) => {
            el.classList.remove('target-highlight');
        });
    }
}
