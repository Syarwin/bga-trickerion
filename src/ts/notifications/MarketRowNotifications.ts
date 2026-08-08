import { formatIcon } from '../format';
import { bga } from '../framework/utils';

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
            const el = $(BUY_SLOT_IDS[i]);
            if (!el) continue;
            const componentType = args.components[i];
            el.innerHTML = componentType ? formatIcon(componentType) : '';
            el.dataset.component = componentType ?? '';
        }
    }

    async notif_quickOrderSet(args: QuickOrderSetArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        (gamedatas.globals.marketRow as any).quickOrder = args.component;
        const el = $(QUICK_ORDER_ID);
        if (el) {
            el.innerHTML = formatIcon(args.component);
            el.dataset.component = args.component;
        }
    }

    async notif_componentOrdered(args: ComponentOrderedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.orderArea[args.slot] = args.component;

        const el = $(ORDER_SLOT_IDS[args.slot]);
        if (el) {
            el.innerHTML = formatIcon(args.component);
            el.dataset.component = args.component;
        }
    }

    async notif_componentArrived(args: ComponentArrivedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.buyArea[args.slot] = args.componentId;
        delete gamedatas.globals.marketRow.orderArea[args.slot];

        const buyEl = $(BUY_SLOT_IDS[args.slot]);
        if (buyEl) {
            buyEl.innerHTML = formatIcon(args.componentId);
            buyEl.dataset.component = args.componentId;
        }

        const orderEl = $(ORDER_SLOT_IDS[args.slot]);
        if (orderEl) {
            orderEl.innerHTML = '';
            delete orderEl.dataset.component;
        }
    }

    async notif_quickOrderCleared(_args: QuickOrderClearedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        gamedatas.globals.marketRow.quickOrder = null;

        const el = $(QUICK_ORDER_ID);
        if (el) {
            el.innerHTML = '';
            delete el.dataset.component;
        }
    }
}
