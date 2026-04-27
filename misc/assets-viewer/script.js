import { cards } from '../../modules/js/Cards.js';
import { staticData } from '../../modules/js/staticData.js';
import { attachRegisteredTooltips } from '../../modules/js/framework/utils.js';

window.$ = (id) => document.getElementById(id);
window._ = (t) => t;
let container;
let addSection = (section, title) => {
    $('game_play_area').insertAdjacentHTML(
        'beforeend',
        `<div class="wrapper" id='${section}-wrapper'>
      <h2>${title}</h2>
      <div class="holder" id="${section}-holder"></div>
    </div>`
    );
    $('menu').insertAdjacentHTML('beforeend', `<a href="#${section}-wrapper">${title}</a>`);
    $(`${section}-wrapper`).addEventListener('click', function () {
        this.classList.toggle('hidden');
    });
    return $(`${section}-holder`);
};

//////////////////////////////////////////////////////////////////////////
//  ____            __
// |  _ \ ___ _ __ / _| ___  _ __ _ __ ___   __ _ _ __   ___ ___  ___
// | |_) / _ \ '__| |_ / _ \| '__| '_ ` _ \ / _` | '_ \ / __/ _ \/ __|
// |  __/  __/ |  |  _| (_) | |  | | | | | | (_| | | | | (_|  __/\__ \
// |_|   \___|_|  |_|  \___/|_|  |_| |_| |_|\__,_|_| |_|\___\___||___/
//////////////////////////////////////////////////////////////////////////

console.log('## Reconstructing performance cards ##', staticData.performances);
container = addSection('performance-cards', 'Performance Cards');

Object.entries(staticData.performances).forEach(([cardType, card]) => {
    card.id = cardType;
    card.type = cardType;
    container.insertAdjacentHTML('beforeend', cards.tplPerformanceCard(card));
});

///////////////////////////////////////////////////////////////
//     _            _                                  _
//    / \   ___ ___(_) __ _ _ __  _ __ ___   ___ _ __ | |_
//   / _ \ / __/ __| |/ _` | '_ \| '_ ` _ \ / _ \ '_ \| __|
//  / ___ \\__ \__ \ | (_| | | | | | | | | |  __/ | | | |_
// /_/   \_\___/___/_|\__, |_| |_|_| |_| |_|\___|_| |_|\__|
//                    |___/
///////////////////////////////////////////////////////////////

console.log('## Reconstructing assignment cards ##', staticData.assignments);
container = addSection('assignment-cards', 'Assignment Cards');

Object.entries(staticData.assignments).forEach(([cardType, card]) => {
    card.id = cardType;
    card.type = cardType;
    container.insertAdjacentHTML('beforeend', cards.tplAssignmentCard(card));
});

////////////////////////////////
//  _____     _      _
// |_   _| __(_) ___| | _____
//   | || '__| |/ __| |/ / __|
//   | || |  | | (__|   <\__ \
//   |_||_|  |_|\___|_|\_\___/
////////////////////////////////

console.log('## Reconstructing trick cards ##', staticData.tricks);
container = addSection('tricks-cards', 'Trick Cards');

Object.entries(staticData.tricks).forEach(([cardType, card]) => {
    card.id = cardType;
    card.type = cardType;
    container.insertAdjacentHTML('beforeend', cards.tplTrickCard(card));
});

/////////////////////////////////////////////////////////
//  ____                  _               _
// |  _ \ _ __ ___  _ __ | |__   ___  ___(_) ___  ___
// | |_) | '__/ _ \| '_ \| '_ \ / _ \/ __| |/ _ \/ __|
// |  __/| | | (_) | |_) | | | |  __/ (__| |  __/\__ \
// |_|   |_|  \___/| .__/|_| |_|\___|\___|_|\___||___/
//                 |_|
/////////////////////////////////////////////////////////

console.log('## Reconstructing prophecies cards ##', staticData.prophecies);
container = addSection('prophecies', 'Prophecies');

Object.entries(staticData.prophecies).forEach(([cardType, card]) => {
    card.id = cardType;
    card.type = cardType;
    container.insertAdjacentHTML('beforeend', cards.tplProphecy(card));
});

/////////////////////////////////////////////////////////
//  __  __             _      _
// |  \/  | __ _  __ _(_) ___(_) __ _ _ __  ___
// | |\/| |/ _` |/ _` | |/ __| |/ _` | '_ \/ __|
// | |  | | (_| | (_| | | (__| | (_| | | | \__ \
// |_|  |_|\__,_|\__, |_|\___|_|\__,_|_| |_|___/
//               |___/
/////////////////////////////////////////////////////////

console.log('## Reconstructing magician cards ##', staticData.magicians);
container = addSection('magicians', 'Magicians');

Object.entries(staticData.magicians).forEach(([cardType, card]) => {
    card.id = cardType;
    card.type = cardType;
    container.insertAdjacentHTML('beforeend', cards.tplMagician(card));
    container.insertAdjacentHTML('beforeend', cards.tplMagician(card, '', true));
    container.insertAdjacentHTML('beforeend', cards.tplMagicianPoster(card));
});

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

attachRegisteredTooltips();
