import { board } from '../../modules/js/Board.js';
import { gamedatas } from './gamedatas.js';
import { cards } from '../../modules/js/Cards.js';
import { isNode } from '../../modules/js/framework/utils.js';
import { meeples } from '../../modules/js/Meeples.js';
window.$ = (id) => (isNode(id) ? id : document.getElementById(id));
window._ = (str) => str;

board.init(gamedatas);
cards.setupPerformanceCards(gamedatas);
