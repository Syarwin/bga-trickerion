import { meeples } from '../Meeples';
import { formatIcon } from '../format';
import { bga } from '../framework/utils';
import { staticData } from '../staticData';

function _trickNameForMarker(tm: TrickMarker, gamedatas: TrickerionGamedatas): string {
    const allTricks: Trick[] = [
        ...Object.values(gamedatas.tricks.player).flat(),
        ...gamedatas.tricks.available,
    ];
    const trick = allTricks.find((t) => t.id === tm.trickId);
    if (trick) {
        const staticTrick = (staticData.tricks as any)[trick.type];
        return staticTrick?.name ?? trick.name ?? trick.type ?? `Trick #${tm.trickId}`;
    }
    return `Trick #${tm.trickId}`;
}

export class TrickMarkerNotifications {
    constructor() {}

    async notif_trickMarkerAddedToPerformance(args: TrickMarkerAddedToPerformanceArgs) {
        this._placeTrickMarker(args.trickMarker, args.performance.id, args.slotId);
    }

    async notif_trickMarkerMovedToPerformance(args: TrickMarkerMovedToPerformanceArgs) {
        this._placeTrickMarker(args.trickMarker, args.performance.id, args.slotId);
    }

    private _placeTrickMarker(tm: TrickMarker, performanceId: number, slotId: string) {
        const oldEl = $(`meeple-${tm.type}-${tm.id}`);
        if (oldEl) oldEl.remove();

        const perfEl = $(`performance-${performanceId}`);
        if (perfEl) {
            const slotEl = perfEl.querySelector(`.trick-marker-slot[data-slotid="${slotId}"]`) as HTMLElement;
            if (slotEl) {
                meeples.addMeeple(tm, slotEl);
                return;
            }
        }

        meeples.addMeeple(tm);
    }

    private async _patchTooltip(meepleEl: HTMLElement, tm: TrickMarker) {
        if (!meepleEl) return;
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        const trickName = _trickNameForMarker(tm, gamedatas);
        const playerName = gamedatas.players[tm.playerId]?.name ?? _('Unknown');

        const tooltip = `<div style="font-family:'Brandon Text Medium';padding:4px">
          <strong>${_(trickName)}</strong><br/>
          ${formatIcon('trick-marker')} ${_(playerName)}<br/>
          <span style="font-size:0.85em;opacity:0.7">${_(tm.suit)}</span>
        </div>`;

        const { addCustomTooltip } = await import('../framework/utils');
        addCustomTooltip(meepleEl, tooltip);
    }

    async notif_trickMarkersReturned(args: TrickMarkersReturnedArgs) {
        for (const tm of args.trickMarkers) {
            const el = $(`meeple-${tm.type}-${tm.id}`);
            if (el) el.remove();
            meeples.addMeeple(tm);
        }
    }
}
