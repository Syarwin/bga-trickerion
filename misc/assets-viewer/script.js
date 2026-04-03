import { formatIcon } from '../../modules/js/format.js';
import { cards } from '../../modules/js/Cards.js';
import { staticData } from '../../modules/js/staticData.js';

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
