import { formatIcon, formatString } from './format.js';
import { staticData } from './staticData.js';
import { registerCustomTooltip } from './framework/utils.js';

export const cards = {
    init: function (gamedatas) {},

    //////////////////////////////////////////////////////////////////////////
    //  ____            __
    // |  _ \ ___ _ __ / _| ___  _ __ _ __ ___   __ _ _ __   ___ ___  ___
    // | |_) / _ \ '__| |_ / _ \| '__| '_ ` _ \ / _` | '_ \ / __/ _ \/ __|
    // |  __/  __/ |  |  _| (_) | |  | | | | | | (_| | | | | (_|  __/\__ \
    // |_|   \___|_|  |_|  \___/|_|  |_| |_| |_|\__,_|_| |_|\___\___||___/
    //////////////////////////////////////////////////////////////////////////

    tplPerformanceCard: function (card, prefix = '') {
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

    ///////////////////////////////////////////////////////////////
    //     _            _                                  _
    //    / \   ___ ___(_) __ _ _ __  _ __ ___   ___ _ __ | |_
    //   / _ \ / __/ __| |/ _` | '_ \| '_ ` _ \ / _ \ '_ \| __|
    //  / ___ \\__ \__ \ | (_| | | | | | | | | |  __/ | | | |_
    // /_/   \_\___/___/_|\__, |_| |_|_| |_| |_|\___|_| |_|\__|
    //                    |___/
    ///////////////////////////////////////////////////////////////
    tplAssignmentCard: function (card, prefix = '') {
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

    tplTrickCard: function (card, prefix = '') {
        card = Object.assign(card, staticData.tricks[card.type]);
        let prefixId = prefix == '' ? '' : `${prefix}-`;
        if (prefix != 'tooltip') {
            registerCustomTooltip(this.tplTrickCard(card, 'tooltip'), `${prefixId}trick-${card.id}`);
        }

        let componentsHTML = '';
        let components = {};
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

    /////////////////////////////////////////////////////////
    //  ____                  _               _
    // |  _ \ _ __ ___  _ __ | |__   ___  ___(_) ___  ___
    // | |_) | '__/ _ \| '_ \| '_ \ / _ \/ __| |/ _ \/ __|
    // |  __/| | | (_) | |_) | | | |  __/ (__| |  __/\__ \
    // |_|   |_|  \___/| .__/|_| |_|\___|\___|_|\___||___/
    //                 |_|
    /////////////////////////////////////////////////////////

    tplProphecy: function (card, prefix = '') {
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
};
