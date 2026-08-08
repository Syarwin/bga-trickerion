import { bga } from '../framework/utils';
import { players } from '../Players';
import { meeples } from '../Meeples';
import { addCustomTooltip } from '../framework/utils';
import { getAnimationManager } from '../libLoader';
import { formatIcon } from '../format';

export class PlayerNotifications {
    async notif_initiativeAdjusted(args: InitiativeAdjustedArgs) {
        await players.onInitiativeAdjusted(args);
    }

    async notif_magicianChosen(args: MagicianChosenArgs) {
        // When a magician is chosen, ensure the UI is updated
        // This can help with synchronization issues during setup
        console.log(players);
        await players.onMagicianChosen(args);
    }

    async notif_componentChanged(args: ComponentChangedArgs) {
        const playerId = args.player_id;
        const component = args.component;
        const count = args.count;

        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        const playerComponents = gamedatas.components.player[playerId];
        if (playerComponents) {
            const existing = playerComponents.find((c: Component) => c.type === component.type);
            if (existing) {
                existing.count = count;
            }
        }

        const meepleEl = $(`meeple-${component.type}-${component.id}`);
        if (meepleEl) {
            if (count <= 0) {
                meepleEl.remove();
            } else {
                meepleEl.dataset.count = String(count);
            }
        } else if (count > 0) {
            meeples.addMeeple(component);
        }
    }

    async notif_coinsChanged(args: CoinsChangedArgs) {
        await players.onCoinsChanged(args);
    }

    async notif_shardsChanged(args: ShardsChangedArgs) {
        await players.onShardsChanged(args);
    }

    async notif_fameChanged(args: FameChangedArgs) {
        const playerId = args.player_id;
        const delta = args.fame;
        const newValue = args.newValue;

        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        if (gamedatas.players[playerId]) {
            (gamedatas.players[playerId] as any).score = String(newValue);
        }

        const fameCounter = players.getCounter(playerId, 'fame');
        if (fameCounter) {
            fameCounter.toValue(newValue);
        }

        const deltaAbs = Math.abs(delta);
        if (deltaAbs > 0) {
            const icon = formatIcon('fame', deltaAbs);
            const titleContainer = document.getElementById('pagemaintitletext');
            if (titleContainer) {
                const animId = `animation-fame-${playerId}`;
                titleContainer.insertAdjacentHTML('beforebegin',
                    `<div id='${animId}' class="animation-elt">${icon}</div>`);

                const targetEl = document.getElementById(`player_board_${playerId}`) ||
                    document.getElementById(`overall_player_board_${playerId}`);

                if (targetEl) {
                    const animationManager = await getAnimationManager();
                    await animationManager.slideFloatingElement($(animId), titleContainer, targetEl, {
                        duration: 1000,
                        fromPlaceholder: 'off',
                        toPlaceholder: 'off',
                    });
                }

                $(animId)?.remove();
            }
        }
    }
}
