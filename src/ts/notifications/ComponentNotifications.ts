export class ComponentNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_componentBought(args: ComponentBoughtArgs) {
    }
    
    async notif_componentMoved(args: ComponentMovedArgs) {
    }
}