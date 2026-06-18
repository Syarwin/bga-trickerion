import { formatIcon, formatString } from './format.js';
import { staticData } from './staticData.js';
import { attachRegisteredTooltips, registerCustomTooltip } from './framework/utils.js';
import { CloseAction, Modal } from './framework/Modal.js';

const TRICK_CATEGORIES = ['spiritual', 'mechanical', 'escape', 'optical'] as const;
type TrickCategory = (typeof TRICK_CATEGORIES)[number];

export const cards = {
    init(gamedatas: TrickerionGamedatas) {
        this.setupPerformanceCards(gamedatas);
        this.setupTrickCards(gamedatas);
    },

    //////////////////////////////////////////////////////////////////////////
    //  ____            __
    // |  _ \ ___ _ __ / _| ___  _ __ _ __ ___   __ _ _ __   ___ ___  ___
    // | |_) / _ \ '__| |_ / _ \| '__| '_ ` _ \ / _` | '_ \ / __/ _ \/ __|
    // |  __/  __/ |  |  _| (_) | |  | | | | | | (_| | | | | (_|  __/\__ \
    // |_|   \___|_|  |_|  \___/|_|  |_| |_| |_|\__,_|_| |_|\___\___||___/
    //////////////////////////////////////////////////////////////////////////

    tplPerformanceCard(card: Performance, prefix: string = '') {
        card = Object.assign(card, staticData.performances[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (prefix != 'tooltip') {
            registerCustomTooltip(this.tplPerformanceCard(card, 'tooltip'), `${prefixId}performance-${card.id}`);
        }

        let slots = '';
        Object.entries(card.slots).forEach(([slotId, slot]) => {
            slots += `<div class="trick-marker-slot" data-slotId="${slotId}" style="grid-row-start:${slot.y + 1}; grid-column-start:${slot.x + 1}"></div>`;
        });

        let bonuses = '';
        if (card.bonus.fame) bonuses += card.bonus.fame + formatIcon('fame');
        if (card.bonus.coins) bonuses += card.bonus.coins + formatIcon('coin');
        if (card.bonus.shards) bonuses += card.bonus.shards + formatIcon('shard');

        return `<div id="${prefixId}performance-${card.id}" class="performance-card ${prefix}" data-type="${card.type}" data-theater="${card.theater}">
        <div class="performance-card-inner">
          <div class="card-name"><span>${_(card.name)}</span></div>
          <div class="card-grid">
            ${slots}
          </div>
          <div class="card-bonus">${bonuses}</div>
        </div>
      </div>`;
    },

    setupPerformanceCards(gamedatas: TrickerionGamedatas) {
        gamedatas.performances.active.forEach((card: Performance) => {
            this.addPerformanceCard(card);
        });
    },

    addPerformanceCard(card: Performance, location: HTMLElement = null) {
        if ($('performance-' + card.id)) return;
        if (location == null) location = this.getPerformanceCardContainer(card);

        $(location).insertAdjacentHTML('beforeend', this.tplPerformanceCard(card));
        attachRegisteredTooltips();
    },

    getPerformanceCardContainer(card: Performance) {
        let container = null;
        if (card.location == 'active') {
            container = `performance-slot-${card.state}`;
        }

        if (!$(container)) {
            console.error('No container found for performance card', card);
            return $('trickerion-main-wrapper');
        }
        return $(container);
    },

    ///////////////////////////////////////////////////////////////
    //     _            _                                  _
    //    / \   ___ ___(_) __ _ _ __  _ __ ___   ___ _ __ | |_
    //   / _ \ / __/ __| |/ _` | '_ \| '_ ` _ \ / _ \ '_ \| __|
    //  / ___ \\__ \__ \ | (_| | | | | | | | | |  __/ | | | |_
    // /_/   \_\___/___/_|\__, |_| |_|_| |_| |_|\___|_| |_|\__|
    //                    |___/
    ///////////////////////////////////////////////////////////////
    tplAssignmentCard(card: Assignment, prefix: string = '') {
        card = Object.assign(card, staticData.assignments[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (prefix != 'tooltip') {
            registerCustomTooltip(this.tplAssignmentCard(card, 'tooltip'), `${prefixId}assignment-card-${card.id}`);
        }

        const assignmentAssetMap = {
            A01_Theater: 'A01_Theater',
            A02_Downtown: 'A02_Downtown',
            A03_MarketRow: 'A03_MarketRow',
            A04_Workshop: 'A04_Workshop',
            A05_DarkAlley: 'A05_DarkAlley',
            A06_GuestPerformer: 'A06_GuestPerformer',
            A07_OrchestralInterlude: 'A06_GuestPerformer',
            A08_HeadlinerTrick: 'A06_GuestPerformer',
            A09_NewTwist: 'A06_GuestPerformer',
            A10_BuyingTime: 'A06_GuestPerformer',
            A11_DurableComponents: 'A11_DurableComponents',
            A12_ArrangedFiasco: 'A11_DurableComponents',
            A13_GrandPremiere: 'A11_DurableComponents',
            A14_StreetPerformance: 'A14_StreetPerformance',
            A15_DirectorsFavor: 'A14_StreetPerformance',
            A16_TrickTweaking: 'A16_TrickTweaking',
            A17_TestRun: 'A16_TrickTweaking',
            A18_ReplacableParts: 'A16_TrickTweaking',
            A19_Invention: 'A16_TrickTweaking',
            A20_Ingenuity: 'A16_TrickTweaking',
            A21_UnstablePrototype: 'A16_TrickTweaking',
            A22_EnhanceTrick: 'A16_TrickTweaking',
            A23_OnStagePreparation: 'A16_TrickTweaking',
            A24_HiddenTalent: 'A16_TrickTweaking',
            A25_Freelancer: 'A25_Freelancer',
            A26_MassRecruitment: 'A26_MassRecruitment',
            A27_HypnoticMotivation: 'A26_MassRecruitment',
            A28_Headhunter: 'A26_MassRecruitment',
            A29_TrickOverhaul: 'A29_TrickOverhaul',
            A30_ThirstForKnowledge: 'A29_TrickOverhaul',
            A31_LeakedBlueprints: 'A29_TrickOverhaul',
            A32_Investments: 'A32_Investments',
            A33_FameAndFortune: 'A32_Investments',
            A34_Interest: 'A32_Investments',
            A35_Empower: 'A35_Empower',
            A36_ComponentSale: 'A36_ComponentSale',
            A37_SmuggledGoods: 'A36_ComponentSale',
            A38_BarterWithPower: 'A36_ComponentSale',
            A39_WorkshopExhibition: 'A36_ComponentSale',
            A40_Shoplifting: 'A36_ComponentSale',
            A45_WorkshopDelivery: 'A36_ComponentSale',
            A41_Logistics: 'A41_Logistics',
            A42_TravelingMerchants: 'A41_Logistics',
            A43_DubiousSources: 'A43_DubiousSources',
            A44_BusinessShare: 'A44_BusinessShare',
        };

        let content = '';
        if (card.abilityText && card.abilityText.length) {
            content += `<div class="card-name">${_(card.name)}</div>`;
            content += `<div class="card-ability">
              <div class="corner"></div>
              ${card.abilityText.map((t) => formatString(_(t))).join('</br>')}
            </div>`;
        }

        return `<div id="${prefixId}assignment-card-${card.id}" class="assignment-card ${prefix}" data-type="${card.type}" data-asset="${assignmentAssetMap[card.type]}">
        <div class="assignment-card-inner">
          ${content}
        </div>
      </div>`;
    },

    ////////////////////////////////
    //  _____     _      _
    // |_   _| __(_) ___| | _____
    //   | || '__| |/ __| |/ / __|
    //   | || |  | | (__|   <\__ \
    //   |_||_|  |_|\___|_|\_\___/
    ////////////////////////////////

    tplTrickCard(card: Trick, prefix: string = '') {
        card = Object.assign(card, staticData.tricks[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (prefix != 'tooltip') {
            registerCustomTooltip(this.tplTrickCard(card, 'tooltip'), `${prefixId}trick-${card.id}`);
        }

        let componentsHTML = '';
        let components: { [key in ComponentType]?: number } = {};
        const componentMapping = {
            fabric: 'basic',
            glass: 'basic',
            metal: 'basic',
            wood: 'basic',
            rope: 'advanced',
            petroleum: 'advanced',
            saw: 'advanced',
            animal: 'advanced',
            padlock: 'superior',
            mirror: 'superior',
            disguise: 'superior',
            cog: 'superior',
        };

        card.componentRequirements.forEach((component) => (components[component] = (components[component] ?? 0) + 1));
        Object.entries(components).forEach(([component, n]) => {
            componentsHTML += `<div class="trick-component" data-n="${n}" data-type="${componentMapping[component]}">`;
            for (let i = 0; i < n; i++) {
                let type = i == n - 1 ? component : componentMapping[component];
                componentsHTML += `<div class="slot-component-${type}"></div>`;
            }
            componentsHTML += '</div>';
        });

        return `<div id="${prefixId}trick-${card.id}" class="trick-card ${prefix}" data-type="${card.type}">
        <div class="trick-card-inner">
          <div class="card-name"><span>${_(card.name)}</span></div>
          <div class="trick-bonus-fame">${card.yields.fame}</div>
          <div class="trick-bonus-coins">${card.yields.coins}</div>
          <div class="symbol-marker-slot"></div>
          <div class="trick-marker-slots" data-n="${card.slots}"></div>
          <div class="trick-components">${componentsHTML}</div>
        </div>
      </div>`;
    },

    setupTrickCards(gamedatas: TrickerionGamedatas) {
        this.setupTrickModal();

        // Setup each player's tricks
        for (const playerIdStr of Object.keys(gamedatas.tricks.player)) {
            const playerId = parseInt(playerIdStr, 10);
            const tricks = gamedatas.tricks.player[playerId];
            for (const trick of tricks) {
                this.addTrickCard(trick);
            }
        }

        // Setup available tricks
        gamedatas.tricks.available.forEach((card) => {
            this.addTrickCard(card);
        });
    },

    setupTrickModal(): void {
        // Create a single shared modal for showing deck contents with tabs
        const deckModal = new Modal('trick-decks', {
            class: 'custom_popin',
            closeIcon: 'fa-times',
            closeAction: CloseAction.Hide,
            closeWhenClickOnUnderlay: true,
            title: _('Trick decks'),
            contents: `<div id="tricks-decks" data-visible="optical">
                <div id="tricks-decks-tabs"></div>
                <div id="tricks-decks-bodies"></div>
            </div>`,
            verticalAlign: 'top',
        });

        // One tab/deck per trick category
        const tabsBar = $('tricks-decks-tabs');
        const bodies = $('tricks-decks-bodies');
        // Setup hidden deck containers, observers
        TRICK_CATEGORIES.forEach((trickCategory) => {
            // Tab
            const tab = document.createElement('div');
            tab.className = 'tab';
            tab.dataset.category = trickCategory;
            tab.innerHTML = `<div class="slot slot-${trickCategory}"></div> ${_(trickCategory)} <div class="slot slot-${trickCategory}"></div>`;
            tab.addEventListener('click', () => ($('tricks-decks').dataset.visible = trickCategory));
            tabsBar.appendChild(tab);

            // Deck
            bodies.insertAdjacentHTML('beforeend', `<div id="${trickCategory}-tricks-deck" class="tricks-deck"></div>`);

            // Add observer to keep the counter updated
            const deckBtn = $(`trick-deck-${trickCategory}`);
            const observer = new MutationObserver((mutationRecords) => {
                mutationRecords.forEach((record) => {
                    $(`trick-deck-${trickCategory}-counter`).innerHTML = String(record.target.childNodes.length);
                });
            });
            observer.observe($(`${trickCategory}-tricks-deck`), { childList: true });
            deckBtn.addEventListener('click', () => {
                $('tricks-decks').dataset.visible = trickCategory;
                deckModal.show();
            });
        });
    },

    addTrickCard(card: Trick, location: HTMLElement = null) {
        if ($('performance-' + card.id)) return;
        if (location == null) location = this.getTrickCardContainer(card);

        $(location).insertAdjacentHTML('beforeend', this.tplTrickCard(card));
        attachRegisteredTooltips();
    },

    getTrickCardContainer(card: Trick) {
        let container = null;

        // Trick on magician workshop => take the first available
        if (card.location === 'player-board') {
            let freeSlots = [...$(`magician-board-${card.playerId}`).querySelectorAll('.magician-workshop .trick-slot')].filter(
                (elt) => elt.childNodes.length == 0
            );
            if (freeSlots.length) container = freeSlots[0];
        }

        // Trick on engineer workshop
        if (card.location === 'engineer-board') {
            let freeSlots = [...$(`magician-board-${card.playerId}`).querySelectorAll('.engineer-workshop .trick-slot')].filter(
                (elt) => elt.childNodes.length == 0
            );
            if (freeSlots.length) container = freeSlots[0];
        }

        // Trick in decks
        if (card.location === 'available') {
            card = Object.assign(card, staticData.tricks[card.type]);
            container = $(`${card.category}-tricks-deck`);
        }

        if (!$(container)) {
            console.error('No container found for trick card', card);
            return $('trickerion-default-container');
        }

        return $(container);
    },

    /////////////////////////////////////////////////////////
    //  ____                  _               _
    // |  _ \ _ __ ___  _ __ | |__   ___  ___(_) ___  ___
    // | |_) | '__/ _ \| '_ \| '_ \ / _ \/ __| |/ _ \/ __|
    // |  __/| | | (_) | |_) | | | |  __/ (__| |  __/\__ \
    // |_|   |_|  \___/| .__/|_| |_|\___|\___|_|\___||___/
    //                 |_|
    /////////////////////////////////////////////////////////

    tplProphecy(card: Prophecy, prefix = '') {
        card = Object.assign(card, staticData.prophecies[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (prefix != 'tooltip') {
            let tooltipContent = `<div class="prophecy-tooltip">
              ${this.tplProphecy(card, 'tooltip')}
              <div class="tooltip-effect">
                ${card.ability.map((t) => _(t)).join('<br/>')}
              </div>
            </div>`;
            registerCustomTooltip(tooltipContent, `${prefixId}prophecy-${card.id}`);
        }

        return `<div id="${prefixId}prophecy-${card.id}" class="prophecy ${prefix}" data-type="${card.type}">
        <div class="prophecy-inner"></div>
      </div>`;
    },

    /////////////////////////////////////////////////////////
    //  __  __             _      _
    // |  \/  | __ _  __ _(_) ___(_) __ _ _ __  ___
    // | |\/| |/ _` |/ _` | |/ __| |/ _` | '_ \/ __|
    // | |  | | (_| | (_| | | (__| | (_| | | | \__ \
    // |_|  |_|\__,_|\__, |_|\___|_|\__,_|_| |_|___/
    //               |___/
    /////////////////////////////////////////////////////////
    tplMagician(card: Magician, prefix = '', isDarkAlley = false) {
        card = Object.assign(card, staticData.magicians[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (isDarkAlley) prefixId += 'alley-';
        if (prefix != 'tooltip') {
            let tooltipContent = `<div class="magician-tooltip">
              ${this.tplMagician(card, 'tooltip', isDarkAlley)}
              <div class="tooltip-effect">
                ${isDarkAlley ? _(card.ability.effect) : ''}
              </div>
            </div>`;
            registerCustomTooltip(tooltipContent, `${prefixId}magician-${card.id}`);
        }

        return `<div id="${prefixId}magician-${card.id}" class="magician-card ${prefix}" data-type="${card.type + (isDarkAlley ? '_alley' : '')}">
        <div class="magician-card-inner">
          <div class="magician-name"><span>${_(card.name)}</span></div>
        </div>
      </div>`;
    },

    tplMagicianPoster(card: Poster, prefix = '') {
        let prefixId = prefix == '' ? '' : `${prefix}-`;

        return `<div id="${prefixId}poster-${card.id}" class="magician-poster ${prefix}" data-type="${card.type}">
        <div class="magician-poster-inner"></div>
      </div>`;
    },
};
