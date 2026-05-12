export class TrickMarkerNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_trickMarkerAddedToPerformance(args: TrickMarkerAddedToPerformanceArgs) {
    }

    async notif_trickMarkerMovedToPerformance(args: TrickMarkerMovedToPerformanceArgs) {
    }

}