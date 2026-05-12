export class TrickNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_trickLearned(args: TrickLearnedArgs) {
    }

    async notif_trickDiscarded(args: TrickDiscardedArgs) {
    }

    async notif_trickPrepared(args: TrickPreparedArgs) {
    }

    async notif_trickMoved(args: TrickMovedArgs) {
    }
}