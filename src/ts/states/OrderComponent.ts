import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';

/** Map order slot index → DOM element id */
const ORDER_SLOT_IDS = ['order-slot-0', 'order-slot-1', 'order-slot-2', 'order-slot-3'];

export class OrderComponent {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: OrderComponentArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: OrderComponentArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        // Phase 1: Show available component types as buttons
        for (const component of args.availableComponents) {
            const label = `${formatIcon(component)} ${_(component)}`;
            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this._showOrderSlots(args, component);
                }
            );
        }
    }

    /** Phase 2: highlight empty order slots on the board + Cancel */
    private _showOrderSlots(args: OrderComponentArgs, component: string) {
        for (const slot of args.availableOrderSlots) {
            const domId = ORDER_SLOT_IDS[slot];
            if (!domId) continue;

            const el = $(domId);
            if (!el) continue;

            el.classList.add('selectable');
            onClick(el, () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actOrderComponents', { component, slotId: slot });
            });
        }

        // Cancel back to Phase 1
        this.bga.statusBar.addActionButton(
            _('Cancel'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (this._currentArgs) this.onPlayerActivationChange(this._currentArgs, true);
            },
            { color: 'alert' }
        );
    }
}
