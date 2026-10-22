import { State } from '../framework/State';
import { onClick } from '../framework/event';
import { clearPossible } from '../framework/utils';
import { formatIcon, translate } from '../format';

/** Map buy slot index → DOM element id */
const BUY_SLOT_IDS = ['buy-slot-0', 'buy-slot-1', 'buy-slot-2', 'buy-slot-3'];

// ========== Helper Functions ==========

/**
 * Get translated and properly formatted string for a component type (capitalized).
 * Maps internal component type IDs to display names.
 */
function getComponentDisplayName(componentType: string): string {
  const componentMap: Record<string, string> = {
    wood: _('Wood'),
    glass: _('Glass'),
    metal: _('Metal'),
    fabric: _('Fabric'),
    rope: _('Rope'),
    petroleum: _('Petroleum'),
    saw: _('Saw'),
    animal: _('Animal'),
    padlock: _('Padlock'),
    mirror: _('Mirror'),
    disguise: _('Disguise'),
    cog: _('Cog'),
  };
  return componentMap[componentType] ?? _(componentType);
}

/**
 * Get translated name of a location.
 * Maps internal location IDs to display names.
 */
function getLocationDisplayName(locationId: string): string {
  const locationMap: Record<string, string> = {
    'player-board': _('Player board'),
    'manager-board': _('Manager board'),
  };
  return locationMap[locationId] ?? _(locationId);
}

// ========== Main Server State ==========

export class BuyComponents extends State {
  onEnteringState(args: BuyComponentsArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    const buyArea = this.game.gamedatas?.globals?.marketRow?.buyArea ?? {};

    // Highlight buy slots that contain a buyable component
    for (let slotIdx = 0; slotIdx < BUY_SLOT_IDS.length; slotIdx++) {
      const componentType = buyArea[slotIdx];
      if (!componentType) continue;
      // Check if this component is actually available to buy
      if (!args.availableComponents[componentType]) continue;

      const componentName = getComponentDisplayName(componentType);
      const callback = () => {
        // Always transition to location client state, which will auto-skip if only one location
        this.bga.states.setClientState('client_buyComponentsLocation', {
          descriptionmyturn: _('Where do want to place the ${comp} once bought?'),
          args: {
            comp: componentName,
            availableComponents: args.availableComponents,
            remainingActionPoints: args.remainingActionPoints,
            selectedComponentType: componentType,
            slotIdx,
          },
        });
      };
      onClick(BUY_SLOT_IDS[slotIdx], callback);
      this.bga.statusBar.addActionButton(componentName, callback);
    }
  }
}

// ========== Client State: Select Location ==========

interface ClientBuyComponentsLocationArgs extends BuyComponentsArgs {
  selectedComponentType: string;
  component: string;
  slotIdx: number;
}

export class ClientBuyComponentsLocation extends State {
  onEnteringState(args: ClientBuyComponentsLocationArgs, isCurrentPlayerActive: boolean) {
    $(BUY_SLOT_IDS[args.slotIdx]).classList.add('selected');
    const componentType = args.selectedComponentType;
    const locations = args.availableComponents[componentType];
    const locationIds = Object.keys(locations);

    // Helper lambda to transition to count selection
    const onChooseLocation = (locationId: string) => {
      this.bga.states.setClientState('client_buyComponentsCount', {
        descriptionmyturn: _('How much ${comp} do you want to buy (${cost}${coin} each)?'),
        args: {
          ...args,
          selectedLocationId: locationId,
          cost: locations[locationId].effectiveCost,
          coin: formatIcon('coin'),
        },
      });
    };

    // Auto-skip if only one location
    if (locationIds.length === 1) {
      onChooseLocation(locationIds[0]);
      return;
    }

    // Show location options
    for (const locationId of locationIds) {
      const cost = locations[locationId].effectiveCost;
      this.bga.statusBar.addActionButton(
        getLocationDisplayName(locationId) + ' ' + translate(_('(${cost}${coin} each)'), { cost, coin: formatIcon('coin') }),
        () => onChooseLocation(locationId)
      );
    }

    this.addCancelStateBtn();
  }
}

// ========== Client State: Select Count ==========

interface ClientBuyComponentsCountArgs extends BuyComponentsArgs {
  selectedComponentType: string;
  selectedLocationId: string;
  slotIdx: number;
}

export class ClientBuyComponentsCount extends State {
  onEnteringState(args: ClientBuyComponentsCountArgs, isCurrentPlayerActive: boolean) {
    $(BUY_SLOT_IDS[args.slotIdx]).classList.add('selected');
    const componentType = args.selectedComponentType;
    const locationId = args.selectedLocationId;
    const { max, maxWithBargain, effectiveCost } = args.availableComponents[componentType][locationId];

    // Show count options
    for (let count = 1; count <= maxWithBargain; count++) {
      const totalCost = effectiveCost * count;
      const isBargain = count > max;

      let btnLabel = `${count} × ${formatIcon(componentType)} → ${totalCost} ${formatIcon('coin')}`;
      if (isBargain || args.remainingActionPoints > 0) {
        // Bargain is possible - go to Phase 3
        this.bga.statusBar.addActionButton(btnLabel, () => {
          this.bga.states.setClientState('client_buyComponentsBargain', {
            descriptionmyturn: _('How much do you want to bargain for ${count} ${comp}(s)'),
            args: {
              ...args,
              selectedCount: count,
              selectedTotalCost: totalCost,
              count,
              comp: getComponentDisplayName(componentType),
            },
          });
        });
      } else {
        // No bargain possible - go directly
        this.addActionBtn(btnLabel, 'actBuyComponents', {
          component: componentType,
          locationId,
          count,
          bargain: 0,
        });
      }
    }

    this.addCancelStateBtn();
  }
}

// ========== Client State: Select Bargain ==========

interface ClientBuyComponentsBargainArgs extends BuyComponentsArgs {
  slotIdx: number;
  selectedComponentType: string;
  selectedLocationId: string;
  selectedCount: number;
  selectedTotalCost: number;
}

export class ClientBuyComponentsBargain extends State {
  onEnteringState(args: ClientBuyComponentsBargainArgs, isCurrentPlayerActive: boolean) {
    $(BUY_SLOT_IDS[args.slotIdx]).classList.add('selected');
    const componentType = args.selectedComponentType;
    const locationId = args.selectedLocationId;
    const count = args.selectedCount;
    const totalCost = args.selectedTotalCost;

    // Buy without bargain
    this.addActionBtn(
      _('Buy for ${cost} ${coin}').replace('${cost}', String(totalCost)).replace('${coin}', formatIcon('coin')),
      'actBuyComponents',
      {
        component: componentType,
        locationId,
        count,
        bargain: 0,
      }
    );

    // Bargain amounts
    if (args.remainingActionPoints > 0 && totalCost > 0) {
      const maxBargain = Math.min(args.remainingActionPoints, totalCost);
      for (let bargain = 1; bargain <= maxBargain; bargain++) {
        const finalCost = totalCost - bargain;
        this.addActionBtn(
          _('Bargain ${n} (pay ${cost} ${coin})')
            .replace('${n}', String(bargain))
            .replace('${cost}', String(finalCost))
            .replace('${coin}', formatIcon('coin')),
          'actBuyComponents',
          {
            component: componentType,
            locationId,
            count,
            bargain,
          }
        );
      }
    }

    this.addCancelStateBtn();
  }
}
