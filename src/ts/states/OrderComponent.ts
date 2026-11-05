import { State } from '../framework/State';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon, getComponentDisplayName } from '../format';

/** Map order slot index → DOM element id */
const ORDER_SLOT_IDS = ['order-slot-0', 'order-slot-1', 'order-slot-2', 'order-slot-3'];

// ========== Server State: Phase 1 - Select Component Type ==========

export class OrderComponent extends State {
  onEnteringState(args: OrderComponentArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    // Phase 1: Show available component types as buttons
    for (const componentType of args.availableComponents) {
      const comp = getComponentDisplayName(componentType);
      this.bga.statusBar.addActionButton(comp, () => {
        this.bga.states.setClientState('client_orderComponentSlot', {
          descriptionmyturn: _('Where do you want to order the ${comp}?'),
          args: {
            availableOrderSlots: args.availableOrderSlots,
            comp,
            selectedComponent: componentType,
          },
        });
      });
    }
  }
}

// ========== Client State: Phase 2 - Select Order Slot ==========

interface ClientOrderComponentSlotArgs extends OrderComponentArgs {
  selectedComponent: string;
}

export class ClientOrderComponentSlot extends State {
  onEnteringState(args: ClientOrderComponentSlotArgs, isCurrentPlayerActive: boolean) {
    for (const slot of args.availableOrderSlots) {
      const domId = ORDER_SLOT_IDS[slot];
      this.actionOnClick($(domId), 'actOrderComponents', {
        componentType: args.selectedComponent,
        slotId: slot,
      });
    }

    this.addCancelStateBtn();
  }
}
