export class DieNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_diceRolled(args: DiceRolledArgs) {
    }
    
    async notif_dieMadeUnavailable(args: DiceMadeUnavailableArgs) {
    }
    
    async notif_dieRerolled(args: DiceRerolledArgs) {
    }
    
    async notif_dieSet(args: DiceSetArgs) {
    }
}