import { formatIcon } from '../format';
import { bga } from '../framework/utils';
import { meeples } from '../Meeples';

const BUY_SLOT_IDS = ['buy-slot-0', 'buy-slot-1', 'buy-slot-2', 'buy-slot-3'];
const ORDER_SLOT_IDS = ['order-slot-0', 'order-slot-1', 'order-slot-2', 'order-slot-3'];
const QUICK_ORDER_ID = 'quick-order-slot';

export class MarketRowNotifications {
    constructor() {}

    async notif_buyAreaSet(args: BuyAreaSetArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.buyArea = {};
        for (let i = 0; i < args.components.length; i++) {
            gamedatas.globals.marketRow.buyArea[i] = args.components[i];
        }

        for (let i = 0; i < BUY_SLOT_IDS.length; i++) {
            const slotId = BUY_SLOT_IDS[i];
            const el = $(slotId);
            if (!el) continue;
            const componentType = args.components[i];
            
            // Remove old meeple if exists
            const oldMeeple = el.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            el.dataset.component = componentType ?? '';
            
            if (componentType) {
                // Create new component meeple
                const component: any = {
                    id: `market-buy-${i}`,
                    type: componentType,
                    location: slotId,
                    count: 1,
                };
                meeples.addMeeple(component, el);
            }
        }
    }

    async notif_quickOrderSet(args: QuickOrderSetArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        (gamedatas.globals.marketRow as any).quickOrder = args.component;
        const el = $(QUICK_ORDER_ID);
        if (el) {
            // Remove old meeple if exists
            const oldMeeple = el.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            el.dataset.component = args.component;
            
            // Create new quick order component meeple
            const component: any = {
                id: 'market-quick-order',
                type: args.component,
                location: QUICK_ORDER_ID,
                count: 1,
            };
            meeples.addMeeple(component, el);
        }
    }

    async notif_componentOrdered(args: ComponentOrderedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.orderArea[args.slot] = args.component;

        const slotId = ORDER_SLOT_IDS[args.slot];
        const el = $(slotId);
        if (el) {
            // Remove old meeple if exists
            const oldMeeple = el.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            el.dataset.component = args.component;
            
            // Create new order component meeple
            const component: any = {
                id: `market-order-${args.slot}`,
                type: args.component,
                location: slotId,
                count: 1,
            };
            meeples.addMeeple(component, el);
        }
    }

    async notif_componentArrived(args: ComponentArrivedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.buyArea[args.slot] = args.componentId;
        delete gamedatas.globals.marketRow.orderArea[args.slot];

        const buySlotId = BUY_SLOT_IDS[args.slot];
        const orderSlotId = ORDER_SLOT_IDS[args.slot];
        
        // Update buy slot - remove old and add new meeple
        const buyEl = $(buySlotId);
        if (buyEl) {
            const oldMeeple = buyEl.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            buyEl.dataset.component = args.componentId;
            
            const component: any = {
                id: `market-buy-${args.slot}`,
                type: args.componentId,
                location: buySlotId,
                count: 1,
            };
            meeples.addMeeple(component, buyEl);
        }

        // Clear order slot
        const orderEl = $(orderSlotId);
        if (orderEl) {
            const oldMeeple = orderEl.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            delete orderEl.dataset.component;
        }
    }

    async notif_quickOrderCleared(_args: QuickOrderClearedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.quickOrder = null;

        const el = $(QUICK_ORDER_ID);
        if (el) {
            const oldMeeple = el.querySelector('.trickerion-meeple');
            if (oldMeeple) oldMeeple.remove();
            
            delete el.dataset.component;
        }
    }
}
