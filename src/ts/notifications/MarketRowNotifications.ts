export class MarketRowNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_buyAreaSet(args: BuyAreaSetArgs) {
    }
    
    async notif_quickOrderSet(args: QuickOrderSetArgs) {
    }
    
    async notif_componentOrdered(args: ComponentOrderedArgs) {
    }

    async notif_componentArrived(args: ComponentArrivedArgs) {
    }
    
    async notif_quickOrderCleared(args: QuickOrderClearedArgs) {
    }

}