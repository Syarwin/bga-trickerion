export class AssignmentNotifications {
    bga: ExtendedBga;
    
    constructor(bga: ExtendedBga) {
        this.bga = bga;
    }

    async notif_assignmentsReset(args: AssignmentResetArgs) {
    }

    async notif_assignmentsReturned(args: AssignmentReturnedArgs) {
    }

    async notif_assignmentsDiscarded(args: AssignmentDiscardedArgs) {
    }

    async assignmentsDrawn(args: AssignmentsDrawnArgs) {
    }

    async assignmentsDiscarded(args: AssignmentsDiscardedArgs) {
    }
    
    async assignmentAssigned(args: AssignmentAssignedArgs) {
    }
}