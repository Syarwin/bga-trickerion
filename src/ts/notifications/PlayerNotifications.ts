export class PlayerNotifications {
    bga: ExtendedBga;
    private players: any = null;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }
    
    setPlayers(players: any): void {
        this.players = players;
    }

    async notif_initiativeAdjusted(args: InitiativeAdjustedArgs) {
        if (this.players) {
            await this.players.onInitiativeAdjusted(args);
        }
    }

    async notif_componentChanged(args: ComponentChangedArgs) {
    }

    async notif_coinsChanged(args: CoinsChangedArgs) {
        if (this.players) {
            await this.players.onCoinsChanged(args);
        }
    }

    async notif_shardsChanged(args: ShardsChangedArgs) {
        if (this.players) {
            await this.players.onShardsChanged(args);
        }
    }

    async notif_fameChanged(args: FameChangedArgs) {
    }
}