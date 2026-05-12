export class PosterNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_postersReturned(args: PostersReturnedArgs) {
    }
}