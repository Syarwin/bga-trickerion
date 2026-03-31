import { board } from '../../modules/js/Board.js';
import { gamedatas } from './gamedatas.js';
window.$ = (id) => document.getElementById(id);
window._ = (str) => str;

board.init(gamedatas);
