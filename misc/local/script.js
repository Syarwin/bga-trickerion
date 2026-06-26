window.$ = (id) => (isNode(id) ? id : document.getElementById(id));
window._ = (str) => str;

// Pre-load bga-animations and bga-dice for local testing.
// On the BGA platform these are loaded via importEsmLib().
import * as BgaAnimationsModule from './bga-animations.esm.js';
import * as BgaDiceModule from './bga-dice.esm.js';

window.__bgaAnimations = BgaAnimationsModule;
window.__bgaDice = BgaDiceModule;

import { board } from '../../modules/js/Board.js';
import { gamedatas } from './gamedatas.js';
import { cards } from '../../modules/js/Cards.js';
import { attachRegisteredTooltips, isNode } from '../../modules/js/framework/utils.js';
import { meeples } from '../../modules/js/Meeples.js';

board.init(gamedatas);
cards.init(gamedatas);
meeples.init(gamedatas);
attachRegisteredTooltips();
