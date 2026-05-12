export class PlayerNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_initiativeAdjusted(args: InitiativeAdjustedArgs) {
    }

    async componentChanged(args: ComponentChangedArgs) {
    }

    async coinsChanged(args: CoinsChangedArgs) {
    }

    async shardsChanged(args: ShardsChangedArgs) {
    }

    async fameChanged(args: FameChangedArgs) {
    }
}