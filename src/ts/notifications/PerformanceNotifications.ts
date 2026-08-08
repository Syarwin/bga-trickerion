import { cards } from '../Cards';
import { bga, attachRegisteredTooltips } from '../framework/utils';
import { formatIcon } from '../format';
import { meeples } from '../Meeples';
import { getAnimationManager } from '../libLoader';

function perfContainer(state: number): string {
    return `performance-slot-${state}`;
}

export class PerformanceNotifications {
    constructor() {}

    async notif_performanceRemoved(args: PerformanceRemovedArgs) {
        const el = $(`performance-${args.performance.id}`);
        if (el) {
            el.classList.add('performance-card-removed');
            el.addEventListener('animationend', () => el.remove(), { once: true });
            setTimeout(() => { if (el.parentNode) el.remove(); }, 600);
        }
    }

    async notif_performancesRotated(_args: PerformancesRotatedArgs) {
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        const activePerfs = gamedatas.performances.active;

        for (const perf of activePerfs) {
            const existing = $(`performance-${perf.id}`);
            if (existing) existing.remove();

            const container = perfContainer(perf.state ?? 1);
            const containerEl = $(container);
            if (containerEl) {
                containerEl.insertAdjacentHTML('beforeend', cards.tplPerformanceCard(perf));
            }
        }
        attachRegisteredTooltips();
    }

    async notif_performanceRevealed(args: PerformanceRevealedArgs) {
        cards.addPerformanceCard(args.performance);
        attachRegisteredTooltips();
    }

    async notif_linkMatched(args: LinkMatchedArgs) {
        const perfEl = $(`performance-${args.performanceId}`);
        if (!perfEl) return;

        const slotEl = perfEl.querySelector(`.trick-marker-slot[data-slotid="${args.slotId}"]`) as HTMLElement;
        if (slotEl) {
            slotEl.classList.add('link-matched');
            setTimeout(() => slotEl.classList.remove('link-matched'), 2000);
        }
    }

    async notif_trickPerformed(args: TrickPerformedArgs) {
        const playerId = args.player_id;
        const yields = args.yields;

        const titleContainer = document.getElementById('pagemaintitletext');
        if (!titleContainer) return;

        const parts: string[] = [];
        if (yields.fame > 0) parts.push(formatIcon('fame', yields.fame));
        if (yields.coins > 0) parts.push(formatIcon('coin', yields.coins));
        if (yields.shards > 0) parts.push(formatIcon('shard', yields.shards));

        if (!parts.length) return;

        const animId = `animation-perform-${args.trick.id}`;
        titleContainer.insertAdjacentHTML('beforebegin',
            `<div id='${animId}' class="animation-elt">${parts.join(' ')}</div>`);

        const targetEl = document.getElementById(`player_board_${playerId}`);
        if (targetEl) {
            const animationManager = await getAnimationManager();
            await animationManager.slideFloatingElement($(animId), titleContainer, targetEl, {
                duration: 1200,
                fromPlaceholder: 'off',
                toPlaceholder: 'off',
            });
        }
        $(animId)?.remove();
    }

    async notif_trickMarkersReturned(args: TrickMarkersReturnedArgs) {
        for (const tm of args.trickMarkers) {
            const el = $(`meeple-${tm.type}-${tm.id}`);
            if (el) el.remove();
            meeples.addMeeple(tm);
        }
    }
}
