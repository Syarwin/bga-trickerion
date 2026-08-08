import { board } from '../Board';
import { meeples } from '../Meeples';
import { getAnimationManager } from '../libLoader';
import { formatIcon } from '../format';
import { bga } from '../framework/utils';

export class CharacterNotifications {
    constructor() {}

    async notif_characterHired(args: CharacterHiredArgs) {
        const playerId = args.player_id;
        const character = args.character;
        const isSpecialist = ['engineer', 'manager', 'assistant'].includes(character.type);
        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;

        // 1. If specialist, update the board to show the new workshop section
        if (isSpecialist) {
            const player = gamedatas.players[playerId];
            const hiredSpecialists = gamedatas.characters.hiredSpecialists[playerId] ?? [];
            if (!hiredSpecialists.includes(character.type)) {
                hiredSpecialists.push(character.type);
            }
            board.updateHiredSpecialists(player, hiredSpecialists);
        }

        // 2. Find the target idle slot using the meeple container logic,
        //    which already handles all location→DOM id mappings correctly.
        const targetEl = meeples.getMeepleContainer(character);
        if (!targetEl || targetEl.id === 'trickerion-default-container') {
            // Fallback: no visible slot, just add the meeple
            meeples.addMeeple(character);
            return;
        }

        const titleContainer = document.getElementById('pagemaintitletext');

        // 3. Create a temporary animation element BEFORE the title (as a sibling)
        //    so it can float freely with absolute positioning relative to the page.
        const animId = `animation-hire-${character.id}`;
        const iconHtml = formatIcon(character.type);
        const animEl = document.createElement('span');
        animEl.id = animId;
        animEl.className = 'animation-elt';
        animEl.style.display = 'inline-block';
        animEl.style.fontSize = '32px';
        animEl.innerHTML = iconHtml;

        if (titleContainer) {
            titleContainer.insertAdjacentElement('beforebegin', animEl);
        } else {
            // No title container — no animation possible, place directly
            meeples.addMeeple(character);
            return;
        }

        // 4. Slide from the title to the target slot
        const animationManager = await getAnimationManager();
        try {
            await animationManager.slideFloatingElement($(animId), titleContainer, targetEl, {
                duration: 800,
                fromPlaceholder: 'off',
                toPlaceholder: 'off',
            });
        } catch (e) {
            // If the slide animation fails, just clean up
            $(animId)?.remove();
            meeples.addMeeple(character);
            return;
        }

        // 5. Remove the temporary animation element
        $(animId)?.remove();

        // 6. Create the actual meeple directly in the idle slot
        const meepleInfos = meeples.tplMeeple(character) as { html: string; tooltip: string };
        targetEl.insertAdjacentHTML('beforeend', meepleInfos.html);
        const newMeeple = targetEl.lastElementChild as HTMLElement;
        if (newMeeple && meepleInfos.tooltip) {
            const { addCustomTooltip } = await import('../framework/utils');
            addCustomTooltip(newMeeple, meepleInfos.tooltip);
        }
    }

    async notif_charactersReturned(_args: CharacterReturnedArgs) {
        document.querySelectorAll('.trickerion-meeple.meeple-character').forEach((el) => {
            const parent = el.parentElement;
            if (parent && (parent.id.startsWith('board-') || parent.closest('[id^="board-"]'))) {
                el.remove();
            }
        });

        const gamedatas = bga.gameui.gamedatas as TrickerionGamedatas;
        for (const character of gamedatas.characters.visible) {
            if (character.location === 'supply' || character.location === 'incoming') continue;
            if ($(`meeple-${character.type}-${character.id}`)) continue;
            meeples.addMeeple(character);
        }
    }

    async notif_wagesPaid(_args: WagesPaidArgs) {}

    async notif_characterPlaced(args: CharacterPlacedArgs) {
        meeples.addMeeple(args.character);
    }

    async notif_apprenticeMovedToAssistant(args: ApprenticeMovedToAssistantArgs) {
        const character = args.character;
        const oldMeeple = $(`meeple-${character.type}-${character.id}`);
        if (oldMeeple) oldMeeple.remove();
        meeples.addMeeple(character);
    }
}
