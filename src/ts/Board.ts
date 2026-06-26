import { formatIcon, formatString } from './format';
import { cards } from './Cards';
import { meeples } from './Meeples';
import { addCustomTooltip } from './framework/utils';
import { getAnimationManager } from './libLoader';
import { dice } from './Dice';

export const board = {
    _isDarkAlley: false,

    isDarkAlley: function () {
        return this._isDarkAlley;
    },

    getAnimationManager: function () {
        return getAnimationManager();
    },

    init: async function (gamedatas: TrickerionGamedatas) {
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

            // Attach tooltips for per-player workshop actions
            this.attachActionTooltips(player.id);
        });

        // Attach tooltips for board-level action spaces (downtown, market, alley, theater)
        this.attachActionTooltips();

        // Initialize dice display
        this.getAnimationManager(); // kick off loading in background
        dice.init(gamedatas).then(() => {
            dice.setupDebugPanel();
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

    // Tooltip descriptions for action spaces — fill in the strings below
    // Use <iconname> for icons (e.g. <coin>, <shard>, <action-point>, <fame>)
    // Use {{text}} for emphasis
    getActionTooltips(): Record<string, Array<string>> {
        const tooltips = {
            // Downtown
            'downtown-learn-trick': [
                _('{{Learn trick:}}'),
                _(
                    'The player chooses a Trick from the Dahlgaard Residence, puts it on an empty Trick slot in their Workshop, and places one of their unused Symbol markers on it.'
                ),
                _(
                    "The category of the newly learned Trick must correspond with the symbol on one of the Dahlgaard Residence dice. The '?' symbol means that any Trick category can be chosen."
                ),
                _("After learning the Trick, set the corresponding die to its 'X' face."),
                _(
                    "The player can always choose to learn a Trick from their Magician's Favorite Trick category instead of the ones available. A chosen die still has to be set to its 'X' face if a Trick is learned this way."
                ),
            ],
            'downtown-hire-character': [
                _('{{Hire character:}}'),
                _(
                    'The player chooses an Inn die and places an unused Character corresponding to the die roll from their supply on the Inn.'
                ),
                _(
                    "The chosen die is then set to its 'X' face. During the 'Return Characters' phase at the end of the turn, the hired Character is added to the player's team."
                ),
                _(
                    "If it was a Specialist, its Board Extension is also added to the player's Game Board. A player may only hire one of each type of Specialist (Assistant, Engineer, Manager)."
                ),
            ],
            'downtown-take-coins': [
                _('{{Take coins:}}'),
                _(
                    "The player chooses a Bank die and takes Coins equal to the die roll from the supply. Then, the player sets the chosen die to its 'X' face."
                ),
            ],
            'downtown-reroll-die': [
                _('{{Reroll die:}}'),
                _(
                    'The player may reroll a Dahlgaard Residence, Inn or Bank die. As a result, the Tricks, Characters and Coins available in the Downtown may change.'
                ),
            ],
            'downtown-set-die': [
                _('{{Set die:}}'),
                _(
                    "The player may freely change the result of any one die roll (e.g. change an 'X' roll to a Manager to hire it later)."
                ),
            ],
            // Market Row
            'market-buy': [
                _('{{Buy:}}'),
                _(
                    "For 1 Action Point, the player may buy up to three Components of the same type. They also have to pay the price of each Component bought (1/2/3 Coins per Basic/Advanced/Superior Component). Only Components in the Market Row's Buy area (the current stock) can be bought this way."
                ),
                _(
                    'Acquired Components are placed on the Component slots on the Player Game Board. Different Components occupy separate slots, but multiples of the same type are piled onto each other. The maximum number a player may have of a Component is 3. Players may return Components to the general supply at any time to make room for new ones.'
                ),
            ],
            'market-bargain': [
                _('{{Bargain:}}'),
                _(
                    "You may only use Bargain together with a 'Buy' Action. You may decrease the total price of Components you buy by 1 Coin per Action Point spent on 'Bargain'. You may never decrease the total price to 0."
                ),
            ],
            'market-order': [
                _('{{Order:}}'),
                _(
                    'If players need a Component which is not currently available in the Market Row, they have to Order it. For 1 Action Point, the player may place a Component from the supply onto an open slot in the Order area (as long as the same Component type is not already there).'
                ),
                _(
                    "These Components will be moved to the corresponding slot in the Buy area during the 'Orders Arrive' phase, and will be available for purchase in the following turn."
                ),
            ],
            'market-quick-order': [
                _('{{Quick order:}}'),
                _(
                    "In some situations, players can't afford to wait a turn for the desired Components. For 2 Action Points, a player may place any Component onto the Quick Order slot in the Market Row (if the slot isn't empty, return the Component there to the supply first)."
                ),
                _(
                    "That Component becomes part of the Market Row's stock this turn (for all players), and can be acquired with the 'Buy' Action. The Coin price of the Component on the Quick Order slot is increased by 1. During the 'End Turn' phase, the Component on the Quick Order slot is returned to the supply"
                ),
            ],
            // Dark Alley
            'alley-draw-first-card': [
                _('{{Draw first card:}}'),
                _(
                    'The player draws two cards from any Special Assignment deck in the Dark Alley, chooses one to keep, and puts the other at the bottom of the deck. A player may only take this Action once per Character placement.'
                ),
            ],
            'alley-draw-further-cards': [
                _('{{Draw further cards:}}'),
                _("This Action is resolved the same way as the 'Draw First Card' Action, except it costs two Action Points."),
            ],
            'alley-fortune-telling': [
                _('{{Fortune telling:}}'),
                _('The player may move each Pending Prophecy one slot in a clockwise direction.'),
            ],
            // Theater
            'theater-set-up-trick': [
                _('{{Set up Trick:}}'),
                _(
                    'The player may move one of their Trick markers from a Trick in the Workshop onto a free slot on a Performance card of their choice – this Trick will be represented by the marker until it is either Performed or the Performance card is discarded at the end of a turn'
                ),
                _(
                    "The Trick marker's corner that corresponds with the Trick's category must be in a Link circle connecting two slots. Two Trick markers of the same color AND the same symbol (e.g. two blue Spades) cannot be in the same Performance."
                ),
                _(
                    'After setting up a Trick marker, if two of the same Trick category symbols are in the same Link circle, those two Tricks are linked. The player who created the Link(s) immediately receives a bonus for each Link, depending on the Level of the Trick they placed to create the Link.'
                ),
                _(
                    'Additionally, if there is a Shard symbol in the Link circle where the Link is created, {{each player with a Trick marker}} in the Link also immediately receives one Trickerion Shard. If a player creates a Link over a Shard symbol with their own Trick, they only receive a single Shard, not two.'
                ),
                _(
                    'It is possible to create multiple Links with a single Trick marker placement. Creating Links is not obligatory.'
                ),
            ],
            'theater-reschedule': [
                _('{{Reschedule:}}'),
                _(
                    "You may move one of your Trick markers from a Performance card to a free slot on the same or any other Performance card. Rules of Trick marker placement apply. You don't receive Link bonuses for moving a Trick marker this way."
                ),
            ],
            // Workshop (per player — {playerId} is auto-replaced)
            'workshop-{playerId}-prepare': [
                _('{{Prepare:}}'),
                _(
                    "The number of Action Points required to Prepare each Trick is printed in a circle in the lower left box (the Trick marker slot). Like at other Locations, players may take multiple 'Prepare' Actions with one Character — the only limit is the number of Action Points they have."
                ),
                _(
                    'When a Trick is Prepared, place a number of Trick markers on it equal to the number of overlapping squares in the lower left box. Use Trick markers with the same symbol as the Symbol marker placed on the Trick when it was learned. The maximum number of Trick markers a player can have in the game of a given symbol is 4.'
                ),
                _(
                    'Important: Players can always Prepare a Trick as long as they meet its Component requirements AND have no Trick markers on the Trick card itself (even if they have some in the Theater).'
                ),
                _(
                    "Very Important : {{Components are NOT spent when a Trick is prepared!}} The same Component stack can be used for multiple Tricks requiring it and for multiple 'Prepare' Actions."
                ),
            ],
            'workshop-{playerId}-move-tricks': [
                _('{{Move tricks:}}'),
                _(
                    "The player moves one of their Tricks to the Engineer's Trick slot or exchanges the Trick there with another of their own Tricks."
                ),
            ],
            'workshop-{playerId}-move-components': [
                _('{{Move components:}}'),
                _(
                    "The player moves one of their Component piles to the Manager's Multi Component slot or exchanges the pile there with another one of their Component piles."
                ),
            ],
            'workshop-{playerId}-move-apprentice': [
                _('{{Move apprentice:}}'),
                _(
                    "The player permanently reallocates one of their Apprentices and the Assignment card below it (if any) to the Assistant's Apprentice slot (if it's empty)."
                ),
            ],
            // Shard bonuses
            'slot-workshop-shard-boost': [
                _(
                    'You can buy +1 Action Point {{once}} by paying a Trickerion Shard. You may not buy more than 1 Action Point per Character placement this way.'
                ),
            ],
            'slot-downtown-shard-boost': [
                _(
                    'You can buy +1 Action Point {{once}} by paying a Trickerion Shard. You may not buy more than 1 Action Point per Character placement this way.'
                ),
            ],
            'slot-market-shard-boost': [
                _(
                    'You can buy +1 Action Point {{once}} by paying a Trickerion Shard. You may not buy more than 1 Action Point per Character placement this way.'
                ),
            ],
            'slot-alley-shard-boost': [
                _(
                    'You can buy +1 Action Point {{once}} by paying a Trickerion Shard. You may not buy more than 1 Action Point per Character placement this way.'
                ),
            ],
            'slot-theater-no-shard-boost': [_("You {{can't}} buy Action Point by paying a Trickerion Shard at the theater.")],
            'slot-workshop-prepare-bonus': [
                _("Tricks on the Engineer's Trick slot receive 1 additional Trick marker when Prepared."),
            ],
        };

        return tooltips;
    },

    attachActionTooltips(playerId: number = null) {
        const tooltips = this.getActionTooltips() as Record<string, Array<string>>;
        Object.entries(tooltips).forEach(([id, text]) => {
            if (!text) return;
            const desc = text.map((t) => formatString(t)).join('<br />');

            // For workshop actions, resolve playerId placeholder
            const resolvedId = id.replace('{playerId}', String(playerId ?? 0));
            const elt = $(resolvedId);
            if (elt) {
                addCustomTooltip(elt, desc);
            } else {
                document.querySelectorAll(`.${resolvedId}`).forEach((e) => addCustomTooltip(e as HTMLElement, desc));
            }
        });
    },
};
