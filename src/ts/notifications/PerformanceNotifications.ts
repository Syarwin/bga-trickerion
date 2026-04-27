export class PerformanceNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_performanceRemoved(args: PerformanceRemovedArgs) {
    }

    async notif_performancesRotated(args: PerformancesRotatedArgs) {
    }

    async notif_performanceRevealed(args: PerformanceRevealedArgs) {
    }

    async notif_linkMatched(args: LinkMatchedArgs) {
    }
    
    async notif_trickPerformed(args: TrickPerformedArgs) {
    }

    async notif_trickMarkersReturned(args: TrickMarkersReturnedArgs) {
    }
}