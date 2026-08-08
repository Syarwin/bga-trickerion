import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { formatIcon } from '../format';

/**
 * Advertise visual state.
 *
 * The player optionally pays their initiative in coins to place a poster on the board.
 * Shows the advertise action space highlight + cost with coin icon.
 * Optional action — player can Skip.
 * After advertising, renders the poster element on the board via notif_advertised.
 */
export class Advertise {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: AdvertiseArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: AdvertiseArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        // Remove highlight from advertise action space
        const actionEl = document.getElementById('theater-perform');
        if (actionEl) actionEl.classList.remove('selectable');
    }

    onPlayerActivationChange(args: AdvertiseArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        const cost = args.cost;

        // Highlight the advertise/perfom action space on the theater board
        const actionEl = document.getElementById('theater-perform');
        if (actionEl) {
            actionEl.classList.add('selectable');
        }

        // Main advertise button with cost info
        const label = _('Advertise (${cost} ${coin})')
            .replace('${cost}', String(cost))
            .replace('${coin}', formatIcon('coin'));

        this.bga.statusBar.addActionButton(
            label,
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (actionEl) actionEl.classList.remove('selectable');
                this.bga.actions.performAction('actAdvertise');
            }
        );

        // Skip option
        this.bga.statusBar.addActionButton(
            _('Skip'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (actionEl) actionEl.classList.remove('selectable');
                this.bga.actions.performAction('actSkipAdvertise');
            },
            { color: 'secondary' }
        );
    }

    async notif_advertised(args: AdvertisedArgs) {
        // A poster was placed on the board.
        // Use cards.tplMagicianPoster to render the poster element,
        // then find its container and insert it with a brief animation.
        const playerId = args.player_id;
        const { cards } = await import('../Cards');
        const { attachRegisteredTooltips } = await import('../framework/utils');

        const html = cards.tplMagicianPoster(args.poster, '');
        const container = document.getElementById('board-background');
        if (!container) return;

        // Remove any existing poster for this player
        const previousPoster = container.querySelector(`.magician-poster[data-player-id="${playerId}"]`);
        if (previousPoster) previousPoster.remove();

        // Insert with fade-in
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const posterEl = tempDiv.firstElementChild as HTMLElement;
        if (posterEl) {
            posterEl.dataset.playerId = String(playerId);
            posterEl.classList.add('poster-placed');
            container.appendChild(posterEl);
            attachRegisteredTooltips();

            // Remove animation class after transition
            setTimeout(() => posterEl.classList.remove('poster-placed'), 600);
        }

        // Show poster overlay on magician card
        const magicianCard = document.querySelector(`#magician-board-${playerId} .magician-poster-overlay`);
        if (magicianCard) {
            magicianCard.classList.add('visible');
        }

        // Show poster overlay in player panel
        const playerPanel = document.getElementById(`player_board_${playerId}`);
        if (playerPanel) {
            const panelPosterOverlay = playerPanel.querySelector('.player-panel-poster-overlay');
            if (panelPosterOverlay) {
                panelPosterOverlay.classList.add('visible');
            } else {
                // Create poster overlay if it doesn't exist
                const overlay = document.createElement('div');
                overlay.className = 'player-panel-poster-overlay visible';
                playerPanel.prepend(overlay);
            }
        }
    }
}
