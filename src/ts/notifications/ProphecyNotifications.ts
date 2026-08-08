import { cards } from '../Cards';
import { attachRegisteredTooltips } from '../framework/utils';

const ALLEY_PROPHECY_ACTIVE = 'alley-active-prophecy';
const ALLEY_PROPHECIES_PENDING = 'alley-pending-prophecies';

export class ProphecyNotifications {
    constructor() {}

    async notif_activeProphecyDiscarded(_args: ActiveProphecyDiscardedArgs) {
        const container = $(ALLEY_PROPHECY_ACTIVE);
        if (container) {
            container.innerHTML = '';
        }
    }

    async notif_activeProphecySet(args: ActiveProphecySetArgs) {
        const container = $(ALLEY_PROPHECY_ACTIVE);
        if (!container) return;

        container.innerHTML = cards.tplProphecy(args.prophecy);
        attachRegisteredTooltips();
    }

    async notif_pendingPropheciesRotated(args: PendingPropheciesRotatedArgs) {
        const container = $(ALLEY_PROPHECIES_PENDING);
        if (!container) return;

        container.innerHTML = '';
        for (const prophecy of args.prophecies) {
            container.insertAdjacentHTML('beforeend', cards.tplProphecy(prophecy));
        }
        attachRegisteredTooltips();
    }

    async notif_newPendingProphecyRevealed(args: NewPendingProphecyRevealedArgs) {
        const container = $(ALLEY_PROPHECIES_PENDING);
        if (!container) return;

        container.insertAdjacentHTML('beforeend', cards.tplProphecy(args.prophecy));
        attachRegisteredTooltips();
    }
}
