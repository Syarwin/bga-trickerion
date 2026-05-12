export class CharacterNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_characterHired(args: CharacterHiredArgs) {
    }

    async notif_charactersReturned(args: CharacterReturnedArgs) {
    }

    async notif_wagesPaid(args: WagesPaidArgs) {
    }

    async notif_characterPlaced(args: CharacterPlacedArgs) {
    }
    
    async notif_apprenticeMovedToAssistant(args: ApprenticeMovedToAssistantArgs) {
    }

}