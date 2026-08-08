import { cards } from '../Cards';
import { meeples } from '../Meeples';
import { bga } from '../framework/utils';
import { getAnimationManager } from '../libLoader';

export class TrickNotifications {
    constructor() {}

    async notif_trickLearned(args: TrickLearnedArgs) {
        const oTrick = $(`trick-${args.trick.id}`);
        const targetEl = cards.getTrickCardContainer(args.trick);
        targetEl.insertAdjacentElement('beforeend', oTrick);

        const animationManager = await getAnimationManager();
        const titleContainer = document.getElementById('pagemaintitletext');
        await animationManager.slideIn(oTrick, titleContainer, {
            duration: 800,
            fromPlaceholder: 'off',
            toPlaceholder: 'off',
        });
    }

    async notif_trickDiscarded(args: TrickDiscardedArgs) {
        const el = $(`trick-${args.trick.id}`);
        if (el) {
            el.classList.add('trick-card-discarded');
            el.addEventListener('animationend', () => el.remove(), { once: true });
            setTimeout(() => {
                if (el.parentNode) el.remove();
            }, 600);
        }
    }

    async notif_trickPrepared(args: TrickPreparedArgs) {
        const el = $(`trick-${args.trick.id}`);
        if (el) {
            el.dataset.prepared = 'true';
        }
        
        // Update gamedatas and place trick markers on the trick card
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        
        for (const marker of args.markers ?? []) {
            // Update gamedatas
            if (gamedatas.trickMarkers.available) {
                const availableIndex = gamedatas.trickMarkers.available.findIndex(tm => tm.id === marker.id);
                if (availableIndex !== -1) {
                    gamedatas.trickMarkers.available.splice(availableIndex, 1);
                }
            }
            
            if (!gamedatas.trickMarkers.prepared) {
                gamedatas.trickMarkers.prepared = [];
            }
            
            const existingMarker = gamedatas.trickMarkers.prepared.find(tm => tm.id === marker.id);
            if (existingMarker) {
                Object.assign(existingMarker, marker);
            } else {
                gamedatas.trickMarkers.prepared.push(marker);
            }
            
            // Remove existing meeple if any
            const existingEl = $(`meeple-${marker.type}-${marker.id}`);
            if (existingEl) {
                existingEl.remove();
            }
            
            // Add the marker to the trick card using meeples.addMeeple
            // The getMeepleContainer method should now handle placing prepared markers on trick cards
            if (marker.location === 'prepared' && marker.trickId) {
                meeples.addMeeple(marker);
            }
        }
        
        // Remove any markers that are no longer available
        if (gamedatas.trickMarkers.available) {
            gamedatas.trickMarkers.available = gamedatas.trickMarkers.available.filter(tm => 
                !(args.markers ?? []).some(marker => marker.id === tm.id)
            );
        }
    }

    async notif_trickMoved(args: TrickMovedArgs) {
        const existingEl = $(`trick-${args.trick.id}`);
        if (existingEl) existingEl.remove();
        cards.addTrickCard(args.trick);

        if (args.previousTrick) {
            const prevEl = $(`trick-${args.previousTrick.id}`);
            if (prevEl) prevEl.remove();
            cards.addTrickCard(args.previousTrick);
        }
    }
}
