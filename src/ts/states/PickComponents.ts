import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { formatIcon } from '../format';

/**
 * PickComponents visual state.
 *
 * The player has a budget (totalValue) and can pick components up to that value.
 * Shows remaining budget, available component types as clickable icons with cost.
 * When budget is depleted, auto-resolves. Player can "Done" early.
 */
export class PickComponents {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: PickComponentsArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: PickComponentsArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: PickComponentsArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: PickComponentsArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this._showPicker(args);
    }

    private _showPicker(args: PickComponentsArgs) {
        this.bga.statusBar.removeActionButtons();

        // Show budget info
        const remaining = args.remainingValue;
        const total = args.totalValue;

        // Budget progress display
        this.bga.statusBar.addActionButton(
            _('Budget: ${r}/${t} for ${loc}')
                .replace('${r}', String(remaining))
                .replace('${t}', String(total))
                .replace('${loc}', _(args.location)),
            () => {},
            { disabled: true }
        );

        if (!args.availableComponents.length && remaining <= 0) {
            this.bga.statusBar.addActionButton(
                _('Budget used — resolving...'),
                () => {},
                { disabled: true }
            );
            return;
        }

        // Show available components as clickable icons with cost
        for (const component of args.availableComponents) {
            const cost = this._componentCost(component);
            // Use component icon + name + coin cost
            const icon = formatIcon(component);
            const label = `${icon} ${_(component)} (${cost} ${formatIcon('coin')})`;
            const canAfford = cost <= remaining;

            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actPickComponent', {
                        component: component,
                    });
                },
                { disabled: !canAfford }
            );
        }

        // Done button (optional, lets player stop early)
        if (remaining < total) {
            this.bga.statusBar.addActionButton(
                _('Done'),
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actDone', {});
                },
                { color: 'secondary' }
            );
        }
    }

    private _componentCost(type: string): number {
        const map: Record<string, number> = {
            wood: 1, glass: 1, metal: 1, fabric: 1,
            rope: 2, petroleum: 2, saw: 2, animal: 2,
            padlock: 3, mirror: 3, disguise: 3, cog: 3,
        };
        return map[type] ?? 1;
    }
}
