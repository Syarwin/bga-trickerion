import { meeples } from '../Meeples';
import { formatIcon } from '../format';
import { bga } from '../framework/utils';
import { getAnimationManager } from '../libLoader';

export class ComponentNotifications {
    constructor() {}

    async notif_componentBought(args: ComponentBoughtArgs) {
        const component = args.component;
        const count = args.count;
        const playerId = args.player_id;
        let newCount = count;

        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        const playerComponents = gamedatas.components.player[playerId];
        if (playerComponents) {
            const existing = playerComponents.find((c: Component) => c.type === component.type);
            if (existing) {
                existing.count += count;
                newCount = existing.count;
                existing.location = component.location;
            }
        }

        const meepleEl = $(`meeple-${component.type}-${component.id}`);
        if (meepleEl) {
            meepleEl.dataset.count = String(newCount);
            const newContainer = meeples.getMeepleContainer(component);
            const currentParent = meepleEl.parentElement;
            if (currentParent && currentParent !== newContainer) {
                newContainer.appendChild(meepleEl);
            }
        } else {
            meeples.addMeeple(component);
        }

        if (args.cost > 0) {
            const icon = formatIcon('coin', args.cost);
            const titleContainer = document.getElementById('pagemaintitletext');
            if (titleContainer) {
                const animId = `animation-buy-${component.id}`;
                titleContainer.insertAdjacentHTML('beforebegin',
                    `<div id='${animId}' class="animation-elt">${icon}</div>`);
                const targetEl = document.getElementById(`player_board_${playerId}`);
                if (targetEl) {
                    const animationManager = await getAnimationManager();
                    await animationManager.slideFloatingElement($(animId), titleContainer, targetEl, {
                        duration: 800,
                        fromPlaceholder: 'off',
                        toPlaceholder: 'off',
                    });
                }
                $(animId)?.remove();
            }
        }
    }

    async notif_componentMoved(args: ComponentMovedArgs) {
        const component = args.component;
        const secondComponent = args.secondComponent;

        const oldEl = $(`meeple-${component.type}-${component.id}`);
        if (oldEl) oldEl.remove();
        meeples.addMeeple(component);

        if (secondComponent) {
            const secondOldEl = $(`meeple-${secondComponent.type}-${secondComponent.id}`);
            if (secondOldEl) secondOldEl.remove();
            meeples.addMeeple(secondComponent);
        }
    }
}
