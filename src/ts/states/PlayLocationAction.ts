import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';

/**
 * Map actionId from backend → DOM element id on the board.
 * Workshop actions are per-player, resolved at runtime.
 * 'enhance_character' is handled via shard-boost elements.
 */
const ACTION_DOM_IDS: Record<string, string> = {
    // Downtown
    learn_trick: 'downtown-learn-trick',
    hire_character: 'downtown-hire-character',
    take_coins: 'downtown-take-coins',
    reroll_die: 'downtown-reroll-die',
    set_die: 'downtown-set-die',
    // Market Row
    buy: 'market-buy',
    order: 'market-order',
    quick_order: 'market-quick-order',
    // Theater
    set_up_trick: 'theater-set-up-trick',
    reschedule: 'theater-reschedule',
    // Dark Alley
    draw_Assignment_cards: 'alley-draw-first-card',
    fortune_telling: 'alley-fortune-telling',
};

/** Workshop actions (per-player DOM: workshop-{playerId}-{action}) */
const WORKSHOP_ACTIONS = ['prepare', 'move_trick', 'move_components', 'move_apprentice'];

/**
 * Map backend locationId prefix → shard-boost DOM element id.
 * Resolved by matching the start of the current location string.
 */
const LOCATION_SHARD_BOOST_IDS: Record<string, string> = {
    'board-downtown': 'downtown-shard-boost',
    'board-market-row': 'market-shard-boost',
    'board-dark-alley': 'alley-shard-boost',
    'board-workshop': 'workshop-{playerId}-shard-boost',
};

export class PlayLocationAction {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: PlayLocationActionArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
        clearPossible();

        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        const playerId = getCurrentPlayerId();

        // Handle enhance_character via shard-boost element first
        if (args.availableActions['enhance_character']) {
            this._wireShardBoost(args.availableActions['enhance_character'], args.locationId, playerId);
        }

        for (const [actionId, action] of Object.entries(args.availableActions)) {
            // enhance_character is handled via shard-boost DOM, not as a button
            if (actionId === 'enhance_character') continue;

            const domId = this._resolveDomId(actionId, playerId);
            const el = domId ? $(domId) : null;

            if (el) {
                el.classList.add('selectable');
                onClick(el, () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actPlayAction', { actionId });
                });
            } else {
                // No matching DOM element — fallback to a button
                const cost = action.actionPoints;
                const minCost = action.minActionPoints;
                let costLabel = cost ? `${cost} AP` : minCost ? `min ${minCost} AP` : null;
                const shardCost = action.shardCost;
                if (shardCost) {
                    costLabel = costLabel ? `${costLabel} + ${shardCost} shard` : `${shardCost} shard`;
                }
                const label = actionId.replace(/_/g, ' ') + (costLabel ? ` (${costLabel})` : '');

                this.bga.statusBar.addActionButton(
                    _(label),
                    () => {
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actPlayAction', { actionId });
                    }
                );
            }
        }
    }

    /**
     * Wire the shard-boost element for the current location to the enhance_character action.
     */
    private _wireShardBoost(action: any, locationId: string, playerId: number) {
        // Determine which section of the board we're on
        let shardBoostId: string | null = null;
        for (const [prefix, idTemplate] of Object.entries(LOCATION_SHARD_BOOST_IDS)) {
            if (locationId.startsWith(prefix)) {
                shardBoostId = idTemplate.replace('{playerId}', String(playerId));
                break;
            }
        }

        if (!shardBoostId) return;

        const shardEl = $(shardBoostId);
        if (!shardEl) return;

        shardEl.classList.add('selectable');
        onClick(shardEl, () => {
            clearPossible();
            this.bga.statusBar.removeActionButtons();
            this.bga.actions.performAction('actPlayAction', { actionId: 'enhance_character' });
        });
    }

    /**
     * Resolve a backend action ID to a DOM element ID.
     * Workshop actions are per-player: workshop-{playerId}-{action_suffix}.
     */
    private _resolveDomId(actionId: string, playerId: number): string | null {
        // Check direct map first
        if (ACTION_DOM_IDS[actionId]) {
            return ACTION_DOM_IDS[actionId];
        }

        // Workshop actions
        if (WORKSHOP_ACTIONS.includes(actionId)) {
            // actionId "prepare" → workshop-{playerId}-prepare
            // actionId "move_trick" → workshop-{playerId}-move-tricks
            // actionId "move_components" → workshop-{playerId}-move-components
            // actionId "move_apprentice" → workshop-{playerId}-move-apprentice
            const suffix = actionId.replace(/_/g, '-');
            return `workshop-${playerId}-${suffix}`;
        }

        return null;
    }
}
