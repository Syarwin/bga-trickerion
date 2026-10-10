import { State } from '../framework/State';
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

export class PlayLocationAction extends State {
  onEnteringState(args: PlayLocationActionArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;
    const playerId = getCurrentPlayerId();

    for (const [actionId, action] of Object.entries(args.availableActions)) {
      const domId = this._resolveDomId(actionId, args.locationId, playerId);
      this.actionOnClick(domId, 'actPlayAction', { actionId });
    }
  }

  /**
   * Resolve a backend action ID to a DOM element ID.
   * Workshop actions are per-player: workshop-{playerId}-{action_suffix}.
   */
  private _resolveDomId(actionId: string, locationId: string, playerId: number): string | null {
    // Shard boost
    if (actionId === 'enhance_character') {
      // Determine which section of the board we're on
      for (const [prefix, idTemplate] of Object.entries(LOCATION_SHARD_BOOST_IDS)) {
        if (locationId.startsWith(prefix)) {
          return idTemplate.replace('{playerId}', String(playerId));
        }
      }
    }

    // Check direct map first
    if (ACTION_DOM_IDS[actionId]) {
      return ACTION_DOM_IDS[actionId];
    }

    // Workshop actions
    if (WORKSHOP_ACTIONS.includes(actionId)) {
      const suffix = actionId.replace(/_/g, '-');
      return `workshop-${playerId}-${suffix}`;
    }

    return null;
  }
}
