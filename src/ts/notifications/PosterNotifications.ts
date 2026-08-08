export class PosterNotifications {
    constructor() {}

    async notif_postersReturned(args: PostersReturnedArgs) {
        const posterElements = document.querySelectorAll('[id^="poster-"]');
        posterElements.forEach((el) => el.remove());

        // Hide poster overlays for all players who had posters returned
        for (const poster of args.posters) {
            const playerId = poster.playerId;
            
            // Hide poster overlay on magician card
            const magicianCard = document.querySelector(`#magician-board-${playerId} .magician-poster-overlay`);
            if (magicianCard) {
                magicianCard.classList.remove('visible');
            }

            // Hide poster overlay in player panel
            const playerPanel = document.getElementById(`player_board_${playerId}`);
            if (playerPanel) {
                const panelPosterOverlay = playerPanel.querySelector('.player-panel-poster-overlay');
                if (panelPosterOverlay) {
                    panelPosterOverlay.classList.remove('visible');
                }
            }
        }
    }
}
