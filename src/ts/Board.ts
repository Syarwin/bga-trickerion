import { formatIcon } from './format';
import { cards } from './Cards';
import { meeples } from './Meeples';

export const board = {
    _isDarkAlley: false,
    isDarkAlley: function () {
        return this._isDarkAlley;
    },

    init: function (gamedatas: TrickerionGamedatas) {
        this._isDarkAlley = gamedatas.globals.isDarkAlley;
        let nPlayers = Object.keys(gamedatas.players).length;

        $('game_play_area').insertAdjacentHTML(
            'beforeend',
            `
<div id="modals-content-holder"></div>
<div id="trickerion-main-wrapper">
  <div id="trickerion-pending"></div>
  <div id="trickerion-main-container">
    <div id="trickerion-board-wrapper">
      <div id="trickerion-board">
        <div id="board-background"></div>
        <div id="board-downtown"></div>
        <div id="board-theater"></div>
        <div id="board-market-row"></div>
        <div id="board-dark-alley"></div>
      </div>
    </div>
    <div id="trickerion-player-board-wrapper"></div>
  </div>
</div>

<div id="trickerion-default-container"></div>

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
            actionsHTML += `<div id="downtown-${action}" class="slot-downtown-action-space">
              <div class="slot-downtown-action-${action}"></div>
              <div class="slot-downtown-action-cost">${cost}${formatIcon('action-point')}</div>
            </div>`;
        });
        const downtownSlots = [2, 3, 4, 2];
        let slotsHTML = '<div id="market-action-slots" class="action-slots">';
        downtownSlots.forEach((n, i) => {
            let j = i + 1;
            if (n > nPlayers) return;
            slotsHTML += `<div id="board-downtown-${j}" class="slot-downtown-slot-${j}"></div>`;
        });
        slotsHTML += '</div>';

        const trickDecks = ['mechanical', 'spiritual', 'escape', 'optical'];
        let trickDecksHTML = '<div id="trick-decks">';
        trickDecks.forEach((type) => {
            trickDecksHTML += `<div id="trick-deck-${type}" class="trick-deck">
              <div class="trick-type slot-${type}"></div>
              <span class="trick-counter" id="trick-deck-${type}-counter">1</span> ${_('trick(s)')}
            </div>`;
        });
        trickDecksHTML += '</div>';

        $('board-downtown').insertAdjacentHTML(
            'beforeend',
            `
        <div id="board-downtown-inner">
          <div id="downtown-logo" class="slot-downtown-logo"></div>
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
          ${trickDecksHTML}
          ${actionsHTML}
          ${slotsHTML}
          <div class="slot-downtown-shard-boost"></div>
        </div>`
        );

        ////////////////////
        // MARKET ROW
        const marketActions = {
            buy: 1,
            bargain: 1,
            order: 1,
            'quick-order': 2,
        };
        actionsHTML = '';
        Object.entries(marketActions).forEach(([action, cost]) => {
            actionsHTML += `<div id="market-${action}" class="slot-market-action-space">
              <div class="slot-market-action-${action}"></div>
              <div class="slot-market-action-cost">${cost}${formatIcon('action-point')}</div>
            </div>`;
        });
        const marketSlots = [2, 3, 4, 2];
        slotsHTML = '<div id="market-action-slots" class="action-slots">';
        marketSlots.forEach((n, i) => {
            let j = i + 1;
            if (n > nPlayers) return;
            slotsHTML += `<div id="board-market-row-${j}" class="slot-market-slot-${j}"></div>`;
        });
        slotsHTML += '</div>';

        let marketsHTML = '<div id="buy-area">';
        for (let i = 0; i < 4; i++) {
            marketsHTML += `<div id="buy-slot-${i}" class="slot-market-component-slot"></div>`;
        }
        marketsHTML += '</div>';
        marketsHTML += '<div id="quick-order-slot" class="slot-market-quick-order-slot"></div>';
        marketsHTML += '<div id="order-area">';
        for (let i = 0; i < 4; i++) {
            marketsHTML += `<div id="order-slot-${i}" class="slot-market-component-slot"></div>`;
        }
        marketsHTML += '</div>';

        $('board-market-row').insertAdjacentHTML(
            'beforeend',
            `
        <div id="board-market-row-inner">
          <div id="market-logo" class="slot-market-row-logo"></div>
          <div class="slot-market-costs"></div>
          <div class="slot-market-quick-order-cost"></div>
          <div class="slot-market-shard-boost"></div>
          ${actionsHTML}
          ${slotsHTML}
          ${marketsHTML}
        </div>`
        );

        ////////////////////
        // DARK ALLEY
        const isDarkAlley = this.isDarkAlley();
        if (isDarkAlley) {
            const alleyActions = {
                'draw-first-card': 1,
                'draw-further-cards': 2,
                'fortune-telling': 1,
            };
            actionsHTML = '';
            Object.entries(alleyActions).forEach(([action, cost]) => {
                actionsHTML += `<div id="alley-${action}" class="slot-alley-action-space">
              <div class="slot-alley-action-${action}"></div>
              <div class="slot-alley-action-cost">${cost}${formatIcon('action-point')}</div>
            </div>`;
            });
            const alleySlots = [2, 3, 4, 2];
            slotsHTML = '<div id="alley-action-slots" class="action-slots">';
            alleySlots.forEach((n, i) => {
                let j = i + 1;
                if (n > nPlayers) return;
                slotsHTML += `<div id="board-dark-alley-${j}" class="slot-alley-slot-${j}"></div>`;
            });
            slotsHTML += '</div>';

            const assignmentDecks = ['downtown', 'market-row', 'workshop', 'theater'];
            let assignmentDecksHTML = '<div id="assignment-decks">';
            assignmentDecks.forEach((type) => {
                assignmentDecksHTML += `<div id="assignment-deck-${type}" class="assignment-deck">
              <div class="assignment-type slot-${type}"></div>
              <span class="assignment-counter" id="assignment-deck-${type}-counter">1</span> ${_('card(s)')}
            </div>`;
            });
            assignmentDecksHTML += '</div>';

            $('board-dark-alley').insertAdjacentHTML(
                'beforeend',
                `
              <div id="board-dark-alley-inner">
                <div id="alley-logo" class="slot-dark-alley-logo"></div>
                <div class="slot-alley-shard-boost"></div>
                ${actionsHTML}
                ${slotsHTML}
                ${assignmentDecksHTML}
                <div class="slot-prophecies"></div>
              </div>`
            );
        }

        ////////////////////
        // THEATER
        const theaterActions = {
            'set-up-trick': 1,
            reschedule: 1,
        };
        actionsHTML = '';
        Object.entries(theaterActions).forEach(([action, cost]) => {
            actionsHTML += `<div id="theater-${action}" class="slot-theater-action-space">
            <div class="slot-theater-action-${action}"></div>
            <div class="slot-theater-action-cost">${cost}${formatIcon('action-point')}</div>
          </div>`;
        });
        const theaterDays = ['thursday', 'friday', 'saturday', 'sunday'];
        slotsHTML = '<div class="slot-theater-action-slots">';
        ['basic-1', 'basic-2', 'magician'].forEach((type) => {
            theaterDays.forEach((day) => {
                slotsHTML += `<div id="board-theater-${day}-${type}" class="action-slot-theater"></div>`;
            });
        });
        slotsHTML += '</div>';

        let performancesHTML = '';
        for (let i = 1; i <= nPlayers; i++) {
            performancesHTML += `<div class='performance-slot' id="performance-slot-${i}"></div>`;
        }

        $('board-theater').insertAdjacentHTML(
            'beforeend',
            `
            <div id="board-theater-inner">
              <div id="board-theater-background"></div>
              <div id="theater-logo" class="slot-theater-logo"></div>
              <div class="slot-theater-no-shard-boost"></div>
              <div class="slot-theater-perform"></div>
              <div class="slot-theater-link-bonus"></div>
              <div class="performances-container">${performancesHTML}</div>
              ${actionsHTML}
              ${slotsHTML}
            </div>`
        );

        // Magician boards
        Object.values(gamedatas.players).forEach((player) => {
            this.setupMagicianBoard(player);

            const specialists = gamedatas.characters.hiredSpecialists[player.id];
            this.updateHiredSpecialists(player, specialists);

            const magician = gamedatas.magicians.player[player.id];
            if (magician) {
                this.updateMagicianBoard(player, magician);
            }
        });
    },

    updateMagicianBoard(player: Player, magician: Magician) {
        let oBoard = $(`magician-board-${player.id}`);
        oBoard.dataset.magicianId = `${magician.id}`;
        oBoard.querySelector('.magician-card-holder').innerHTML = cards.tplMagician(magician, '', this.isDarkAlley());
    },

    setupMagicianBoard(player: Player) {
        const isDarkAlley = this.isDarkAlley();

        $('trickerion-player-board-wrapper').insertAdjacentHTML(
            'beforeend',
            `
        <div class="magician-board" id="magician-board-${player.id}" data-magician-id="0">
          <div class="magician-board-inner" style="border-color:#${player.color}">
            <div class="player-name" style="border-color:#${player.color}; background-color:#${player.color}">
              ${player.name}
            </div>
            <div class="slot-workshop-logo"></div>
            <div class="magician-workshop">
              <div class="magician-workshop-main">
                <div class="magician-workshop-background"></div>
                <div class="magician-card-holder"></div>
                <div class="slot-workshop-shard-boost"></div>
                <div id="workshop-${player.id}-prepare" class="slot-workshop-action-space">
                  <div class="slot-workshop-action-prepare"></div>
                  <div class="slot-workshop-action-cost">?${formatIcon('action-point')}</div>
                </div>

                <div class="action-slots">
                  <div id="board-workshop-${player.id}-1" class="slot-workshop-slot-1"></div>
                  <div id="board-workshop-${player.id}-2" class="slot-workshop-slot-1"></div>
                </div>
                <div class="magician-tricks-components">
                  <div class="trick-slot slot-1"></div>
                  <div class="trick-slot slot-2"></div>
                  <div class="trick-slot slot-3"></div>

                  <div class="component-slot slot-workshop-component-slot"></div>
                  <div class="component-slot slot-workshop-component-slot"></div>
                  <div class="component-slot slot-workshop-component-slot"></div>
                  <div class="component-slot slot-workshop-component-slot"></div>
                  <div class="component-slot slot-workshop-component-slot"></div>
                  <div class="component-slot slot-workshop-component-slot"></div>
                </div>
              </div>
              <div class="engineer-workshop">
                <div class="specialist-workshop-background"></div>
                <div class="trick-slot engineer-slot"></div>
                <div class="slot-workshop-prepare-bonus"></div>
                <div id="workshop-${player.id}-move-tricks" class="slot-workshop-action-space">
                  <div class="slot-workshop-action-move-tricks"></div>
                  <div class="slot-workshop-action-cost">1${formatIcon('action-point')}</div>
                </div>
              </div>
              <div class="manager-workshop">
                <div class="manager-components">
                  <div class="component-slot slot-workshop-manager-component-slot"></div>
                  <div class="component-slot slot-workshop-manager-component-slot"></div>
                </div>

                <div class="specialist-workshop-background"></div>
                <div id="workshop-${player.id}-move-components" class="slot-workshop-action-space">
                  <div class="slot-workshop-action-move-components"></div>
                  <div class="slot-workshop-action-cost">1${formatIcon('action-point')}</div>
                </div>
              </div>
              <div class="assistant-workshop">
                <div class="specialist-workshop-background"></div>
                <div id="workshop-${player.id}-move-apprentice" class="slot-workshop-action-space">
                  <div class="slot-workshop-action-move-apprentice"></div>
                  <div class="slot-workshop-action-cost">1${formatIcon('action-point')}</div>
                </div>
              </div>
            </div>
            <div class="magician-assignments" id="assignments-${player.id}" data-color="${player.color}">
              <div class="magician-assignments-main"></div>
              <div class="magician-assignments-specialists"></div>
            </div>          
          </div>
        </div>
      `
        );

        [
            'magician',
            'apprentice-1',
            'apprentice-2',
            'apprentice-3',
            'apprentice-assistant',
            'engineer',
            'manager',
            'assistant',
        ].forEach((typeId, i) => {
            let container = $(`assignments-${player.id}`).querySelector(
                i < 5 ? '.magician-assignments-main' : '.magician-assignments-specialists'
            );
            let type = typeId.split('-')[0];
            let meepleSlotClass = type == 'apprentice' ? 'apprentice-meeple' : 'meeple';
            let slotClass = 'assignment';

            // Assistant special slot
            if (typeId === 'apprentice-assistant') {
                meepleSlotClass = 'assistant-meeple';
                slotClass = 'special-assignment';
            }

            container.insertAdjacentHTML(
                'beforeend',
                `<div class="assignment-slot-holder" id="assignment-slot-${player.id}-${typeId}">
                <div class="assignment-slot-header">
                  <div id="idle-${player.id}-${typeId}" class="slot-assignment-${meepleSlotClass}-slot">${formatIcon(type)}</div>
                </div>
                <div class="assignment-slot slot-${slotClass}-slot"></div>
              </div>`
            );
        });
    },

    updateHiredSpecialists(player: Player, hiredSpecialists: CharacterType[]) {
        let board = $(`magician-board-${player.id}`);
        board.dataset.nSpecialists = '' + hiredSpecialists.length;

        const characterTypes = ['engineer', 'manager', 'assistant'] satisfies CharacterType[];
        characterTypes.forEach((characterType) => {
            const hired = hiredSpecialists.includes(characterType);
            board.querySelector(`.${characterType}-workshop`).classList.toggle('disabled', !hired);
            $(`assignment-slot-${player.id}-${characterType}`).classList.toggle('disabled', !hired);

            if (characterType === 'assistant') {
                $(`assignment-slot-${player.id}-apprentice-assistant`).classList.toggle('disabled', !hired);
            }
        });
    },
};
