/**
 * Dice — manages all bga-dice stocks on the board.
 *
 * On init() it reads gamedatas.globals.dice and creates bga-dice LineStocks
 * for the 6 downtown die slots: character (2), trick (2), bank/money (2).
 *
 * Uses the shared AnimationManager from libLoader.
 * Die faces match the PHP definitions in Managers/Dice.php.
 */

import { formatIcon } from './format';
import { addCustomTooltip } from './framework/utils';
import { getAnimationManager, useDice } from './libLoader';

interface DieData {
    id: number;
    face: number | string;
    type: string;
    slotKey: string;
}

/** The 6 faces for each slot, in order (1-indexed), matching Managers/Dice.php */
const FACE_DEFS: Record<string, (number | string)[]> = {
    'trick-0': ['escape', 'mechanical', 'optical', 'spiritual', 'any', 'not-available'],
    'trick-1': ['escape', 'mechanical', 'optical', 'spiritual', 'any', 'not-available'],
    'character-0': ['not-available', 'not-available', 'not-available', 'assistant', 'manager', 'engineer'],
    'character-1': ['apprentice', 'apprentice', 'apprentice', 'apprentice', 'apprentice', 'not-available'],
    'money-0': [3, 4, 4, 5, 6, 'not-available'],
    'money-1': [3, 4, 4, 5, 6, 'not-available'],
};

/** Human-readable labels for tooltips */
const getFaceLabels = function (): Record<string, Record<string | number, string>> {
    return {
        trick: {
            escape: _('Escape'),
            mechanical: _('Mechanical'),
            optical: _('Optical'),
            spiritual: _('Spiritual'),
            any: _('Any'),
        },
        character: {
            apprentice: _('Apprentice'),
            assistant: _('Assistant'),
            manager: _('Manager'),
            engineer: _('Engineer'),
        },
        money: {},
    };
};

const NOT_AVAILABLE = 'not-available';
const ANY = 'any';

let diceManager: any = null;
let initialized = false;

/** Maps backend slot id → DOM element id */
const SLOT_MAP: Record<string, string> = {
    'character-0': 'die-character-0',
    'character-1': 'die-character-1',
    'trick-0': 'die-trick-0',
    'trick-1': 'die-trick-1',
    'money-0': 'die-bank-0',
    'money-1': 'die-bank-1',
};

/** Stores the created LineStock instances per slot key */
const stocks: Record<string, any> = {};

export const dice = {
    getDiceManager(): any {
        return diceManager;
    },

    /**
     * Initialize all dice from gamedatas.
     * Called from board.init() after the DOM is built.
     */
    async init(gamedatas: TrickerionGamedatas): Promise<void> {
        if (initialized) return;
        initialized = true;

        const BgaDice = await useDice();
        const animationManager = await getAnimationManager();

        diceManager = new BgaDice.Manager({
            type: 'trickerion-die',
            faces: 6,
            size: 50,
            animationManager,
            getDieFace: (die: DieData) => {
                const faces = FACE_DEFS[die.slotKey];
                if (!faces) return 1;
                const index = faces.indexOf(die.face);
                return index >= 0 ? index + 1 : 6; // 1-based; last if unknown
            },
            setupDieDiv: (die: DieData, element: HTMLDivElement) => {
                element.classList.add(`die-${die.type}`);
                element.dataset.type = die.type;
                element.dataset.slotKey = die.slotKey;
            },
            setupFaceDiv: (die: DieData, element: HTMLDivElement, faceIndex: number) => {
                const faces = FACE_DEFS[die.slotKey];
                const faceValue = faces ? faces[faceIndex - 1] : faceIndex;
                element.classList.add(`die-face-${die.type}`);
                element.innerHTML = '';

                if (faceValue === NOT_AVAILABLE) {
                    element.classList.add('die-face-not-available');
                    element.textContent = 'X';
                } else if (faceValue === ANY) {
                    element.classList.add('die-face-any');
                    element.textContent = '?';
                } else if (die.type === 'character' && typeof faceValue === 'string') {
                    element.classList.add('die-face-character-image');
                    element.innerHTML = formatIcon(faceValue);
                } else if (die.type === 'trick' && typeof faceValue === 'string') {
                    element.classList.add(`die-face-trick-${faceValue}`);
                    element.innerHTML = formatIcon(faceValue);
                } else if (die.type === 'money') {
                    element.classList.add(`die-face-money-${faceValue}`);
                    element.innerHTML = `${faceValue} ${formatIcon('coin')}`;
                }
            },
        });

        for (const [slotKey, domId] of Object.entries(SLOT_MAP)) {
            const container = document.getElementById(domId);
            if (!container) continue;
            stocks[slotKey] = new BgaDice.LineStock(diceManager, container, {
                direction: 'row',
                center: true,
            });
        }

        this.updateDice(gamedatas);
    },

    /**
     * Update dice faces from gamedatas data.
     */
    updateDice(gamedatas: TrickerionGamedatas): void {
        const diceData = gamedatas.globals.dice;

        const dieDataMap: Record<string, DieData[]> = {
            character: [
                { id: 1, face: diceData.character[0], type: 'character', slotKey: 'character-0' },
                { id: 2, face: diceData.character[1], type: 'character', slotKey: 'character-1' },
            ],
            trick: [
                { id: 3, face: diceData.trick[0], type: 'trick', slotKey: 'trick-0' },
                { id: 4, face: diceData.trick[1], type: 'trick', slotKey: 'trick-1' },
            ],
            money: [
                { id: 5, face: diceData.money[0], type: 'money', slotKey: 'money-0' },
                { id: 6, face: diceData.money[1], type: 'money', slotKey: 'money-1' },
            ],
        };

        for (const group of Object.values(dieDataMap)) {
            for (const die of group) {
                const stock = stocks[die.slotKey];
                if (!stock) continue;

                stock.removeAll();
                stock.addDice([die]);

                // Attach tooltip showing all possible faces
                const dieContainer = document.getElementById(SLOT_MAP[die.slotKey]);
                if (dieContainer) {
                    this.attachDieTooltip(dieContainer, die);
                }
            }
        }
    },

    /**
     * Attach a tooltip to a die container listing the 6 possible faces.
     * The current face is marked with a highlight.
     */
    attachDieTooltip(container: HTMLElement, die: DieData): void {
        const faces = FACE_DEFS[die.slotKey];
        if (!faces) return;

        const type = die.type;
        const typeDisplay = {
            trick: _('Dahlgaard Residence die'),
            character: _('Inn die'),
            money: _('Bank die'),
        }[type];
        const currentFace = die.face;

        const FACE_LABELS = getFaceLabels();

        let html = `<div class="die-tooltip"><strong>${typeDisplay}</strong><br>`;
        let alreadyCurrent = false;
        faces.forEach((face) => {
            const isCurrent = face === currentFace && !alreadyCurrent;
            alreadyCurrent = alreadyCurrent || isCurrent;

            let label: string;
            if (face === NOT_AVAILABLE) {
                label = 'X';
            } else if (face === ANY) {
                label = '?';
            } else if (typeof face === 'string' && FACE_LABELS[type]?.[face]) {
                label = FACE_LABELS[type][face];
            } else {
                label = `${face}`;
            }
            html += `<span class="${isCurrent ? 'die-tooltip-current' : ''}">${label}</span>`;
            if (faces.indexOf(face) < faces.length - 1) html += ' · ';
        });
        html += '</div>';

        addCustomTooltip(container, html);
    },

    /**
     * Roll a die in the given slot with an animation.
     */
    async rollDie(slotKey: string, newFace: number | string): Promise<void> {
        const stock = stocks[slotKey];
        if (!stock) return;

        const dieData = stock.getDice();
        if (dieData.length === 0) return;

        // Update the face on the die data
        dieData[0].face = newFace;
        stock.rollDie(dieData[0], { effect: 'rollOutPauseAndBack', duration: 1000 });

        // Update tooltip
        const dieContainer = document.getElementById(SLOT_MAP[slotKey]);
        if (dieContainer) {
            this.attachDieTooltip(dieContainer, dieData[0]);
        }
    },

    /**
     * Set up a debug UI panel with buttons to cycle each die through its faces.
     * Only available in local development mode (when importEsmLib is not defined).
     */
    setupDebugPanel(beforeEl?: HTMLElement): void {
        // Only in local mode
        if (true) return;

        const panel = document.createElement('div');
        panel.id = 'dice-debug-panel';
        panel.style.cssText =
            'position:fixed;bottom:10px;right:10px;background:#222;border:1px solid #666;border-radius:8px;padding:10px;z-index:9999;font-family:sans-serif;font-size:12px;max-width:360px;color:#eee';

        panel.innerHTML = `<div style="cursor:pointer;font-weight:bold;margin-bottom:6px" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">🎲 Dice Debug</div>`;
        const body = document.createElement('div');
        body.style.cssText = 'display:block';
        panel.appendChild(body);

        for (const [slotKey, domId] of Object.entries(SLOT_MAP)) {
            const faces = FACE_DEFS[slotKey];
            if (!faces) continue;

            const row = document.createElement('div');
            row.style.cssText = 'margin:3px 0;display:flex;align-items:center;gap:4px;flex-wrap:wrap';
            row.innerHTML = `<span style="min-width:80px;font-weight:bold">${slotKey}</span>`;

            faces.forEach((face) => {
                const label = face === NOT_AVAILABLE ? 'X' : face === ANY ? '?' : `${face}`;
                const btn = document.createElement('button');
                btn.textContent = label;
                btn.style.cssText =
                    'padding:2px 6px;border:1px solid #555;border-radius:4px;background:#444;color:#eee;cursor:pointer;font-size:11px';
                btn.addEventListener('click', () => {
                    this.rollDie(slotKey, face);
                });
                row.appendChild(btn);
            });

            body.appendChild(row);
        }

        document.body.appendChild(panel);
    },
};
