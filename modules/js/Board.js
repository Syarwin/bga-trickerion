export const board = {
    init: function (gamedatas) {
        let nPlayers = Object.keys(gamedatas.players).length;

        $('game_play_area').insertAdjacentHTML(
            'beforeend',
            `
<div id="trickerion-main-wrapper">
  <div id="trickerion-main-container">
    <div id="trickerion-board-wrapper">
      <div id="trickerion-board">
        <div id="board-background"></div>
        <div id="board-downtown"></div>
      </div>
    </div>
    <div id="trickerion-player-board-wrapper"></div>
  </div>
</div>

<div id="floating-board-wrapper">
  <div id="floating-board"></div>
</div>

<svg style="display:none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="map-marker-question" role="img" xmlns="http://www.w3.org/2000/svg">
  <symbol id="help-marker-svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="white" d="M256 8C119 8 8 119.08 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 422a46 46 0 1 1 46-46 46.05 46.05 0 0 1-46 46zm40-131.33V300a12 12 0 0 1-12 12h-56a12 12 0 0 1-12-12v-4c0-41.06 31.13-57.47 54.65-70.66 20.17-11.31 32.54-19 32.54-34 0-19.82-25.27-33-45.7-33-27.19 0-39.44 13.14-57.3 35.79a12 12 0 0 1-16.67 2.13L148.82 170a12 12 0 0 1-2.71-16.26C173.4 113 208.16 90 262.66 90c56.34 0 116.53 44 116.53 102 0 77-83.19 78.21-83.19 106.67z" opacity="1"></path><path class="fa-primary" fill="currentColor" d="M256 338a46 46 0 1 0 46 46 46 46 0 0 0-46-46zm6.66-248c-54.5 0-89.26 23-116.55 63.76a12 12 0 0 0 2.71 16.24l34.7 26.31a12 12 0 0 0 16.67-2.13c17.86-22.65 30.11-35.79 57.3-35.79 20.43 0 45.7 13.14 45.7 33 0 15-12.37 22.66-32.54 34C247.13 238.53 216 254.94 216 296v4a12 12 0 0 0 12 12h56a12 12 0 0 0 12-12v-1.33c0-28.46 83.19-29.67 83.19-106.67 0-58-60.19-102-116.53-102z"></path></g>
  </symbol>
</svg>
`
        );

        ////////////////////
        // DOWNTOWN
        const downtownActions = {
            'hire-character': 3,
            'learn-trick': 3,
            'take-coins': 3,
            'set-die': 2,
            'reroll-die': 1,
        };
        let actionsHTML = '';
        Object.entries(downtownActions).forEach(([action, cost]) => {
            actionsHTML += `<div id="market-${action}" class="slot-downtown-action-space">
              <div class="slot-downtown-action-${action}"></div>
              <div class="slot-downtown-action-cost">${cost}</div>
            </div>`;
        });
        const downtownSlots = [2, 3, 4, 2];
        let slotsHTML = '<div id="market-action-slots" class="action-slots">';
        downtownSlots.forEach((n, i) => {
            let j = i + 1;
            if (n > nPlayers) return;
            slotsHTML += `<div id="board-downtown-${j}" class="slot-downtown-slot-${j}"></div>`;
        });
        slotsHTML += '<div class="slot-downtown-shard-boost"></div>';
        slotsHTML += '</div>';

        $('board-downtown').insertAdjacentHTML(
            'beforeend',
            `
        <div id="board-downtown-inner">
          <div id="downtown-logo" class="slot-downtown"></div>
          <div id="dice-characters">
            <div id="die-character-0" class="slot-downtown-die-slot-character-A"></div>
            <div id="die-character-1" class="slot-downtown-die-slot-character-B"></div>
          </div>
          <div id="dice-tricks">
            <div id="die-trick-0" class="slot-downtown-die-slot-trick-A"></div>
            <div id="die-trick-1" class="slot-downtown-die-slot-trick-B"></div>
          </div>
          <div id="dice-banks">
            <div id="die-bank-0" class="slot-downtown-die-slot-bank-A"></div>
            <div id="die-bank-1" class="slot-downtown-die-slot-bank-B"></div>
          </div>
          ${actionsHTML}
          ${slotsHTML}
        </div>`
        );
    },
};
