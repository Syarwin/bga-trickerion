import { formatIcon } from './format.js';
import { staticData } from './staticData.js';

export const cards = {
    init: function (gamedatas) {},

    tplPerformanceCard: function (card, prefix = '') {
        card = Object.assign(card, staticData.performances[card.type]);

        let slots = '';
        Object.entries(card.slots).forEach(([slotId, slot]) => {
            slots += `<div class="trick-marker-slot" data-slotId="${slotId}" style="grid-row-start:${slot.y + 1}; grid-column-start:${slot.x + 1}"></div>`;
        });

        let bonuses = '';
        if (card.bonus.fame) bonuses += card.bonus.fame + formatIcon('fame');
        if (card.bonus.coins) bonuses += card.bonus.coins + formatIcon('coin');
        if (card.bonus.shards) bonuses += card.bonus.shards + formatIcon('shard');

        return `<div id="performance-${card.id}" class="performance-card" data-type="${card.type}" data-theater="${card.theater}">
        <div class="performance-card-inner">
          <div class="card-name"><span>${_(card.name)}</span></div>
          <div class="card-grid">
            ${slots}
          </div>
          <div class="card-bonus">${bonuses}</div>
        </div>
      </div>`;
    },
};
