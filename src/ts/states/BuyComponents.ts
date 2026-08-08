import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';

/** Map buy slot index → DOM element id */
const BUY_SLOT_IDS = ['buy-slot-0', 'buy-slot-1', 'buy-slot-2', 'buy-slot-3'];

export class BuyComponents {
    game: Game;
    bga: ExtendedBga;
    /** Stash args so Cancel can re-enter Phase 1 */
    private _currentArgs: BuyComponentsArgs | null = null;
    private _isCurrentPlayerActive = false;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: BuyComponentsArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this._isCurrentPlayerActive = isCurrentPlayerActive;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: BuyComponentsArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: BuyComponentsArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this._isCurrentPlayerActive = true;

        this.bga.statusBar.removeActionButtons();

        this._showBuySlots(args);
    }

    /** Phase 1: highlight buy slots that contain a buyable component */
    private _showBuySlots(args: BuyComponentsArgs) {
        const buyArea = this.game.gamedatas?.globals?.marketRow?.buyArea ?? {};

        // Map each buy slot index → componentType (if present)
        for (let slotIdx = 0; slotIdx < BUY_SLOT_IDS.length; slotIdx++) {
            const componentType = buyArea[slotIdx];
            if (!componentType) continue;
            // Check if this component is actually available to buy
            if (!args.availableComponents[componentType]) continue;

            const el = $(BUY_SLOT_IDS[slotIdx]);
            if (!el) continue;

            el.classList.add('selectable');
            onClick(el, () => {
                clearPossible();
                this._showCountSelection(args, componentType);
            });
        }
    }

    /** Phase 2: show count buttons for the selected component */
    private _showCountSelection(args: BuyComponentsArgs, componentType: string) {
        this.bga.statusBar.removeActionButtons();

        const compData = args.availableComponents[componentType];
        if (!compData) return;

        // Find first location with max > 0
        const locationId = Object.keys(compData).find((loc) => compData[loc].max > 0);
        if (!locationId) return;

        const { max, maxWithBargain, effectiveCost } = compData[locationId];
        const label = _(componentType) + ` (${effectiveCost} ${formatIcon('coin')} each)`;

        // Show component info
        this.bga.statusBar.addActionButton(label, () => {}, { disabled: true });

        // Show count options
        for (let count = 1; count <= maxWithBargain; count++) {
            const totalCost = effectiveCost * count;
            const isBargain = count > max;
            let btnLabel = `${count} × ${formatIcon(componentType)} → ${totalCost} ${formatIcon('coin')}`;
            if (isBargain) {
                btnLabel += ` (${_('bargain')})`;
            }

            this.bga.statusBar.addActionButton(
                btnLabel,
                () => {
                    clearPossible();
                    if (args.remainingActionPoints > 0 && count <= max) {
                        this._showBargainOrBuy(args, componentType, locationId, count, totalCost);
                    } else {
                        // No bargain possible — go directly
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actBuyComponents', {
                            component: componentType,
                            locationId,
                            count,
                            bargain: 0,
                        });
                    }
                }
            );
        }

        // Cancel back to Phase 1
        this.bga.statusBar.addActionButton(
            _('Cancel'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (this._currentArgs) this._showBuySlots(this._currentArgs);
            },
            { color: 'alert' }
        );
    }

    /** Phase 3: offer Buy (no bargain) or pick a bargain amount */
    private _showBargainOrBuy(
        args: BuyComponentsArgs,
        componentType: string,
        locationId: string,
        count: number,
        totalCost: number
    ) {
        this.bga.statusBar.removeActionButtons();

        // Buy without bargain
        this.bga.statusBar.addActionButton(
            _('Buy for ${cost} ${coin}').replace('${cost}', String(totalCost)).replace('${coin}', formatIcon('coin')),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actBuyComponents', {
                    component: componentType,
                    locationId,
                    count,
                    bargain: 0,
                });
            }
        );

        // Bargain amounts
        if (args.remainingActionPoints > 0 && totalCost > 0) {
            const maxBargain = Math.min(args.remainingActionPoints, totalCost);
            for (let bargain = 1; bargain <= maxBargain; bargain++) {
                const finalCost = totalCost - bargain;
                this.bga.statusBar.addActionButton(
                    _('Bargain ${n} (pay ${cost} ${coin})')
                        .replace('${n}', String(bargain))
                        .replace('${cost}', String(finalCost))
                        .replace('${coin}', formatIcon('coin')),
                    () => {
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actBuyComponents', {
                            component: componentType,
                            locationId,
                            count,
                            bargain,
                        });
                    }
                );
            }
        }

        // Cancel back to Phase 2
        this.bga.statusBar.addActionButton(
            _('Back'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (this._currentArgs) this._showCountSelection(this._currentArgs, componentType);
            },
            { color: 'alert' }
        );
    }
}
