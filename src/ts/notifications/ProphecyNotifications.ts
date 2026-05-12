export class ProphecyNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_activeProphecyDiscarded(args: ActiveProphecyDiscardedArgs) {
    }
    
    async notif_activeProphecySet(args: ActiveProphecySetArgs) {
    }
    
    async notif_pendingPropheciesRotated(args: PendingPropheciesRotatedArgs) {
    }
    
    async notif_newPendingProphecyRevealed(args: NewPendingProphecyRevealedArgs) {
    }
    
}