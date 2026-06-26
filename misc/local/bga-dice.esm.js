function styleInject(css, ref) {
  if ( ref === void 0 ) ref = {};
  var insertAt = ref.insertAt;

  if (typeof document === 'undefined') { return; }

  var head = document.head || document.getElementsByTagName('head')[0];
  var style = document.createElement('style');
  style.type = 'text/css';

  if (insertAt === 'top') {
    if (head.firstChild) {
      head.insertBefore(style, head.firstChild);
    } else {
      head.appendChild(style);
    }
  } else {
    head.appendChild(style);
  }

  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }
}

var css_248z = "body>.bga-dice_die{left:-9999px;position:absolute;top:-9999px}.bga-dice_die{--bga-dice_die-selection-size:5px;--bga-dice_die-selectable-color:orange;--bga-dice_die-selected-color:#8a2be2;--bga-dice_die-disabled-contrast:0.6;display:inline-block;flex-shrink:0;height:var(--size);position:relative;transform-origin:center center;transform-style:preserve-3d;width:var(--size)}.bga-dice_die.bga-dice_selectable-die{cursor:pointer;outline:var(--bga-dice_die-selection-size) dashed var(--bga-dice_die-selectable-color)}.bga-dice_die.bga-dice_selected-die{cursor:pointer;outline:var(--bga-dice_die-selection-size) solid var(--bga-dice_die-selected-color)}.bga-dice_die.bga-dice_disabled-die{cursor:not-allowed;filter:contrast(var(--bga-dice_die-disabled-contrast))}.bga-dice_die .bga-dice_die-faces{display:inline-block;display:grid;grid-template-columns:1fr;grid-template-rows:1fr;height:100%;position:relative;transform-origin:center center;transform-style:preserve-3d;width:100%}.bga-dice_die .bga-dice_die-faces .bga-dice_die-face{display:inline-block;grid-column:1;grid-row:1;height:var(--size);height:100%;position:absolute;width:var(--size);width:100%}.bga-dice_die4{--third-size:calc(var(--size)/3*0.6);transform:translateZ(var(--third-size))}.bga-dice_die4 .bga-dice_die-faces[data-visible-face=\"1\"]{transform:rotate(0deg) rotateX(0deg)}.bga-dice_die4 .bga-dice_die-faces[data-visible-face=\"2\"]{transform:rotateY(180deg) rotate(0deg) rotateX(70.528779deg)}.bga-dice_die4 .bga-dice_die-faces[data-visible-face=\"3\"]{transform:rotate(180deg) rotateX(250deg) rotate(-120deg)}.bga-dice_die4 .bga-dice_die-faces[data-visible-face=\"4\"]{transform:rotateX(120deg) rotate(-60deg)}.bga-dice_die4 .bga-dice_die-faces .bga-dice_die-face{clip-path:polygon(50% 0,0 100%,100% 100%);height:86.6%;margin-left:calc(var-(--size)*-.5);margin-top:calc(var-(--size)*-.5);transform-origin:50% 66.66667%}.bga-dice_die4 .bga-dice_die-faces .bga-dice_die-face[data-face=\"1\"]{transform:rotateY(180deg) translateZ(var(--third-size))}.bga-dice_die4 .bga-dice_die-faces .bga-dice_die-face[data-face=\"2\"]{transform:rotate(0deg) rotateX(-70.528779deg) translateZ(var(--third-size))}.bga-dice_die4 .bga-dice_die-faces .bga-dice_die-face[data-face=\"3\"]{transform:rotate(120deg) rotateX(-70.528779deg) translateZ(var(--third-size))}.bga-dice_die4 .bga-dice_die-faces .bga-dice_die-face[data-face=\"4\"]{transform:rotate(240deg) rotateX(-70.528779deg) translateZ(var(--third-size))}.bga-dice_die6{--half-size:calc(var(--size)/2);border-radius:var(--bga-dice_border-radius);transform:translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"1\"]{transform:rotate3d(0,0,0,0deg)}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"2\"]{transform:rotateX(90deg)}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"3\"]{transform:rotate3d(0,-1,0,90deg)}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"4\"]{transform:rotateY(90deg)}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"5\"]{transform:rotate3d(-1,0,0,90deg)}.bga-dice_die6 .bga-dice_die-faces[data-visible-face=\"6\"]{transform:rotateY(180deg)}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face{border-radius:var(--bga-dice_border-radius)}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"1\"]{transform:rotate3d(0,0,0,0deg) translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"2\"]{transform:rotate3d(-1,0,0,90deg) translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"3\"]{transform:rotateY(90deg) translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"4\"]{transform:rotate3d(0,-1,0,90deg) translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"5\"]{transform:rotateX(90deg) translateZ(var(--half-size))}.bga-dice_die6 .bga-dice_die-faces .bga-dice_die-face[data-face=\"6\"]{transform:rotateY(180deg) translateZ(var(--half-size))}.bga-dice_die8{--half-size:calc(var(--size)/2);transform:translateZ(var(--half-size))}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"1\"]{transform:rotateY(0deg) rotateX(-35.27deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"2\"]{transform:rotateY(270deg) rotateX(-35.27deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"3\"]{transform:rotateY(180deg) rotateX(-35.27deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"4\"]{transform:rotateY(90deg) rotateX(-35.27deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"5\"]{transform:rotateY(0deg) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"6\"]{transform:rotateY(270deg) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"7\"]{transform:rotateY(180deg) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces[data-visible-face=\"8\"]{transform:rotateY(90deg) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face{bottom:50%;clip-path:polygon(50% 0,0 100%,100% 100%);height:calc(var(--half-size)*1.732);left:0;position:absolute;transform-origin:50% 100%;width:var(--size)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"1\"]{transform:rotateY(0deg) translateZ(var(--half-size)) rotateX(35.27deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"2\"]{transform:rotateY(90deg) translateZ(var(--half-size)) rotateX(35.27deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"3\"]{transform:rotateY(180deg) translateZ(var(--half-size)) rotateX(35.27deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"4\"]{transform:rotateY(270deg) translateZ(var(--half-size)) rotateX(35.27deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"5\"]{transform:rotateY(1turn) translateZ(var(--half-size)) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"6\"]{transform:rotateY(450deg) translateZ(var(--half-size)) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"7\"]{transform:rotateY(540deg) translateZ(var(--half-size)) rotateX(144.73deg)}.bga-dice_die8 .bga-dice_die-faces .bga-dice_die-face[data-face=\"8\"]{transform:rotateY(630deg) translateZ(var(--half-size)) rotateX(144.73deg)}.bga-dice_die-stock{perspective:var(--perspective);transform-origin:center center;transform-style:preserve-3d}.bga-dice_die-stock.bga-dice_line-stock{display:flex;flex-direction:var(--direction);flex-wrap:var(--wrap);gap:var(--gap)}.bga-dice_die-stock.bga-dice_line-stock[data-center=true]{justify-content:center}.bga-dice_die-stock.bga-dice_slot-stock{display:flex;flex-direction:var(--direction);flex-wrap:var(--wrap);gap:var(--gap)}.bga-dice_die-stock.bga-dice_slot-stock[data-center=true]{justify-content:center}.bga-dice_die-stock.bga-dice_manual-position-stock{position:relative}.bga-dice_die-stock.bga-dice_manual-position-stock .bga-dice_die{position:absolute}.bga-dice_die-stock.bga-dice_void-stock{position:relative}.bga-dice_die-stock.bga-dice_void-stock .bga-dice_die{position:absolute}";
styleInject(css_248z);

/******************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */
/* global Reflect, Promise, SuppressedError, Symbol, Iterator */


function __awaiter(thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
}

typeof SuppressedError === "function" ? SuppressedError : function (error, suppressed, message) {
    var e = new Error(message);
    return e.name = "SuppressedError", e.error = error, e.suppressed = suppressed, e;
};

function diceInputToArray(dice) {
    return Array.isArray(dice) ? dice : Object.values(dice);
}

class DiceManager {
    /**
     * @param settings a `DiceManagerSettings` object
     */
    constructor(settings) {
        this.settings = settings;
        this.stocks = [];
        if (!settings || !settings.animationManager) {
            throw new Error('You must define an AnimationManager in the settings');
        }
        this.animationManager = settings.animationManager;
        if (![4, 6, 8].includes(this.getFaces())) {
            throw new Error('Unsupported settings.faces');
        }
    }
    addStock(stock) {
        const existingStock = this.stocks.find(existingStock => existingStock.getElement() === stock.getElement());
        if (existingStock) {
            console.error('A stock is already registered on this element. Old stock:', existingStock, 'New stock:', stock);
        }
        this.stocks.push(stock);
    }
    getFaces() {
        var _a;
        return (_a = this.settings.faces) !== null && _a !== void 0 ? _a : 6;
    }
    getSize() {
        var _a;
        return (_a = this.settings.size) !== null && _a !== void 0 ? _a : 50;
    }
    getBorderRadius() {
        var _a;
        return (_a = this.settings.borderRadius) !== null && _a !== void 0 ? _a : (this.getFaces() === 6 ? 5 : 0);
    }
    /**
     * @returns the filtering to apply on card click events. Default 'selectable'.
     */
    getDieClickEventFilter() {
        var _a, _b;
        return (_b = (_a = this.settings) === null || _a === void 0 ? void 0 : _a.dieClickEventFilter) !== null && _b !== void 0 ? _b : 'selectable';
    }
    /**
     * @param die the die informations
     * @return the id for a die
     */
    getId(die) {
        var _a, _b, _c;
        return (_c = (_b = (_a = this.settings).getId) === null || _b === void 0 ? void 0 : _b.call(_a, die)) !== null && _c !== void 0 ? _c : `${die.id}`;
    }
    /**
     * @param die the die informations
     * @return the id for a die element
     */
    getDieElementId(die) {
        return `${this.getType()}-${this.getId(die)}`;
    }
    /**
     *
     * @returns the type of the dice, either set in the settings or by using a default name if there is only 1 type.
     */
    getType() {
        var _a;
        return (_a = this.settings.type) !== null && _a !== void 0 ? _a : `game-dice`;
    }
    /**
     * Return the die face.
     * Default: the face will be set to `die.face`.
     *
     * @param die the die informations
     * @return the die face
     */
    getDieFace(die) {
        var _a, _b, _c;
        return (_c = (_b = (_a = this.settings).getDieFace) === null || _b === void 0 ? void 0 : _b.call(_a, die)) !== null && _c !== void 0 ? _c : die.face;
    }
    createDieElement(die) {
        var _a, _b;
        const id = this.getDieElementId(die);
        if (this.getDieElement(die)) {
            throw new Error(`This die already exists ${JSON.stringify(die)}`);
        }
        const faces = this.getFaces();
        const type = this.getType();
        const element = document.createElement("div");
        element.id = id;
        element.classList.add('bga-dice_die', `bga-dice_die${faces}`, type);
        element.style.setProperty('--size', `${this.getSize()}px`);
        element.style.setProperty('--bga-dice_border-radius', `${this.getBorderRadius()}%`);
        const dieFaces = document.createElement("div");
        dieFaces.classList.add('bga-dice_die-faces');
        dieFaces.dataset.visibleFace = '' + this.getDieFace(die);
        element.appendChild(dieFaces);
        const facesElements = [];
        for (let i = 1; i <= faces; i++) {
            facesElements[i] = document.createElement("div");
            facesElements[i].id = `${id}-face-${i}`;
            facesElements[i].classList.add('bga-dice_die-face', `${type}-face-${i}`);
            facesElements[i].dataset.face = '' + i;
            dieFaces.appendChild(facesElements[i]);
        }
        document.body.appendChild(element);
        (_b = (_a = this.settings).setupDieDiv) === null || _b === void 0 ? void 0 : _b.call(_a, die, element);
        if (this.settings.setupFaceDiv) {
            for (let i = 1; i <= faces; i++) {
                this.settings.setupFaceDiv(die, facesElements[i], i);
            }
        }
        document.body.removeChild(element);
        return element;
    }
    /**
     * @param die the die informations
     * @return the HTML element of an existing die
     */
    getDieElement(die) {
        return document.getElementById(this.getDieElementId(die));
    }
    /**
     * Remove a die.
     *
     * @param die the die to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDie(die, settings) {
        var _a, _b, _c;
        const div = this.getDieElement(die);
        if (!div) {
            return Promise.resolve(false);
        }
        div.id = `deleted-${div.id}`;
        let promise = Promise.resolve(true);
        if ((_a = settings === null || settings === void 0 ? void 0 : settings.slideTo) !== null && _a !== void 0 ? _a : undefined) {
            if ((_b = settings.fadeOut) !== null && _b !== void 0 ? _b : true) {
                promise = this.animationManager.fadeOutAndDestroy(div, settings.slideTo);
            }
            else {
                promise = this.animationManager.slideOutAndDestroy(div, settings.slideTo);
            }
        }
        else {
            div.remove();
        }
        // if the die is in a stock, notify the stock about removal
        (_c = this.getDieStock(die)) === null || _c === void 0 ? void 0 : _c.dieRemoved(die);
        return promise;
    }
    /**
     * Returns the stock containing the die.
     *
     * @param die the die informations
     * @return the stock containing the die
     */
    getDieStock(die) {
        return this.stocks.find(stock => stock.contains(die));
    }
    /**
     * Update the die informations. Used when a change visible face.
     *
     * @param die the die informations
     */
    updateDieInformations(die, updateData) {
        const div = this.getDieElement(die);
        div.querySelector('.bga-dice_die-faces').dataset.visibleFace = '' + this.getDieFace(die);
        if (updateData !== null && updateData !== void 0 ? updateData : true) {
            // die data has changed
            const stock = this.getDieStock(die);
            const dice = stock.getDice();
            const dieIndex = dice.findIndex(c => this.getId(c) === this.getId(die));
            if (dieIndex !== -1) {
                stock.dice.splice(dieIndex, 1, die);
            }
        }
    }
    /**
     * @returns the default perspective for all stocks.
     */
    getPerspective() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.perspective) === undefined ? 1000 : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.perspective;
    }
    /**
     * @returns the class to apply to selectable dice. Default 'bga-dice_selectable-die'.
     */
    getSelectableDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.selectableDieClass) === undefined ? 'bga-dice_selectable-die' : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.selectableDieClass;
    }
    /**
     * @returns the class to apply to selectable dice. Default 'bga-dice_disabled-die'.
     */
    getUnselectableDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.unselectableDieClass) === undefined ? 'bga-dice_disabled-die' : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.unselectableDieClass;
    }
    /**
     * @returns the class to apply to selected dice. Default 'bga-dice_selected-die'.
     */
    getSelectedDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.selectedDieClass) === undefined ? 'bga-dice_selected-die' : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.selectedDieClass;
    }
    /**
     * Place a die based on the autoPlace settings of each stock.
     */
    placeDie(die) {
        return __awaiter(this, void 0, void 0, function* () {
            var _a, _b, _c, _d;
            const matchingStock = this.stocks.find(stock => stock.shouldPlaceDie(die));
            if (matchingStock) {
                const addSettings = (_b = (_a = this.settings.autoPlace) === null || _a === void 0 ? void 0 : _a.addSettings) === null || _b === void 0 ? void 0 : _b.call(_a, die);
                return matchingStock.addDie(die, addSettings);
            }
            else {
                const removeSettings = (_d = (_c = this.settings.autoPlace) === null || _c === void 0 ? void 0 : _c.removeSettings) === null || _d === void 0 ? void 0 : _d.call(_c, die);
                if (removeSettings) {
                    return this.removeDie(die, removeSettings);
                }
                else if (this.stocks.length === 1) {
                    // only 1 stock, place the die on it
                    return this.stocks[0].addDie(die);
                }
                else {
                    throw new Error('Impossible to auto place die ' + JSON.stringify(die));
                }
            }
        });
    }
    /**
     * Place some dice based on the autoPlace settings of each stock.
     */
    placeDice(dice) {
        return __awaiter(this, void 0, void 0, function* () {
            return diceInputToArray(dice).map(die => this.placeDie(die));
        });
    }
}

function sort(...sortedFields) {
    return (a, b) => {
        for (let i = 0; i < sortedFields.length; i++) {
            let direction = 1;
            let field = sortedFields[i];
            if (field[0] == '-') {
                direction = -1;
                field = field.substring(1);
            }
            else if (field[0] == '+') {
                field = field.substring(1);
            }
            const type = typeof a[field];
            if (type === 'string') {
                const compare = a[field].localeCompare(b[field]);
                if (compare !== 0) {
                    return compare * direction;
                }
            }
            else if (type === 'number') {
                const compare = (a[field] - b[field]);
                if (compare !== 0) {
                    return compare * direction;
                }
            }
        }
        return 0;
    };
}

/**
 * The abstract stock. It shouldn't be used directly, use stocks that extends it.
 */
class DiceStock {
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager, element, settings) {
        this.manager = manager;
        this.element = element;
        this.settings = settings;
        this.dice = [];
        this.selectableDice = [];
        this.selectedDice = [];
        this.selectionMode = 'none';
        manager.addStock(this);
        element === null || element === void 0 ? void 0 : element.classList.add('bga-dice_die-stock' /*, this.constructor.name.split(/(?=[A-Z])/).join('-').toLowerCase()* doesn't work in production because of minification */);
        const perspective = this.getPerspective();
        element.style.setProperty('--perspective', perspective ? `${perspective}px` : 'unset');
        this.bindClick();
        this.sort = settings === null || settings === void 0 ? void 0 : settings.sort;
    }
    /**
     * @returns the dice on the stock
     */
    getDice() {
        return this.dice.slice();
    }
    /**
     * @returns the HTML element used by this stock
     */
    getElement() {
        return this.element;
    }
    /**
     * @returns if the stock is empty
     */
    isEmpty() {
        return !this.dice.length;
    }
    /**
     * @returns the selected dice
     */
    getSelection() {
        return this.selectedDice.slice();
    }
    /**
     * @returns if the card is selectable
     */
    isSelectable(die) {
        return this.selectableDice.some(c => this.manager.getId(c) == this.manager.getId(die));
    }
    /**
     * @returns the selected dice
     */
    isSelected(die) {
        return this.selectedDice.some(c => this.manager.getId(c) == this.manager.getId(die));
    }
    /**
     * @param die a die
     * @returns if the die is present in the stock
     */
    contains(die) {
        return this.dice.some(c => this.manager.getId(c) == this.manager.getId(die));
    }
    /**
     * @param die a die in the stock
     * @returns the HTML element generated for the die
     */
    getDieElement(die) {
        return this.manager.getDieElement(die);
    }
    /**
     * Checks if the die can be added. By default, only if it isn't already present in the stock.
     *
     * @param die the die to add
     * @param settings the addDie settings
     * @returns if the die can be added
     */
    canAddDie(die, settings) {
        return !this.contains(die);
    }
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die, settings) {
        if (!this.canAddDie(die, settings)) {
            return Promise.resolve(false);
        }
        // we check if die is in a stock
        let dieElement = this.getDieElement(die);
        const originStock = this.manager.getDieStock(die);
        if (dieElement && !originStock) {
            let errorMessage = 'The die element exists but is not attached to any Stock. ';
            if (dieElement.querySelector('.bga-dice_die-faces')) {
                errorMessage += 'The element is most likely a die, the stock containing it might have been removed without a proper cleanup.';
            }
            else {
                errorMessage += 'The element is probably not a die, make sure you do not create an element with an id that could conflict with die id ' + dieElement.id + '.';
            }
            console.error(errorMessage + ' Element:', dieElement);
            throw new Error('The die element exists but is not attached to any Stock');
        }
        if (dieElement) { // unselect the die
            originStock.unselectDie(die);
            this.removeSelectionClassesFromElement(dieElement);
        }
        const addDieSettings = settings !== null && settings !== void 0 ? settings : {};
        if (originStock) { // if the die is in a Stock, the animation must come from it
            addDieSettings.fromStock = originStock;
        }
        const index = this.getNewDieIndex(die);
        if (index !== undefined) {
            addDieSettings.index = index;
        }
        if (addDieSettings.index !== null && addDieSettings.index !== undefined) {
            this.dice.splice(index, 0, die);
        }
        else {
            this.dice.push(die);
        }
        let promise = dieElement ?
            this.addExistingDieElement(die, dieElement, addDieSettings) :
            this.addUnexistingDieElement(die, addDieSettings);
        this.manager.updateDieInformations(die);
        // if the die was from a stock, we remove the die from it. 
        // Must be called after the animation is started, so it doesn't delete the element
        if (addDieSettings.fromStock && addDieSettings.fromStock != this) {
            addDieSettings.fromStock.removeDie(die);
        }
        if (this.selectionMode !== 'none') {
            // make selectable only at the end of the animation
            promise.then(() => { var _a; return this.setSelectableDie(die, (_a = addDieSettings.selectable) !== null && _a !== void 0 ? _a : true); });
        }
        return promise;
    }
    addExistingDieElement(die, dieElement, settings) {
        var _a, _b, _c;
        const toElement = (_a = settings === null || settings === void 0 ? void 0 : settings.forceToElement) !== null && _a !== void 0 ? _a : this.element;
        let insertBefore = undefined;
        if ((settings === null || settings === void 0 ? void 0 : settings.index) === null || (settings === null || settings === void 0 ? void 0 : settings.index) === undefined || !toElement.children.length || (settings === null || settings === void 0 ? void 0 : settings.index) >= toElement.children.length) ;
        else {
            insertBefore = toElement.children[settings.index];
        }
        const promise = this.animationFromElement(die, dieElement, (_c = (_b = settings.fromStock) === null || _b === void 0 ? void 0 : _b.element) !== null && _c !== void 0 ? _c : settings.fromElement, toElement, insertBefore, settings);
        return promise;
    }
    addUnexistingDieElement(die, settings) {
        const dieElement = this.manager.createDieElement(die);
        return this.addExistingDieElement(die, dieElement, settings);
    }
    /**
     * @param element The element to animate. The element is added to the destination stock before the animation starts.
     * @param toElement The HTMLElement to attach the die to.
     */
    animationFromElement(die, element, fromElement, toElement, insertBefore, settings) {
        return __awaiter(this, void 0, void 0, function* () {
            var _a, _b, _c;
            if (document.contains(element)) {
                const result = yield this.manager.animationManager.slideAndAttach(element, toElement, settings, insertBefore);
                return (_a = result === null || result === void 0 ? void 0 : result.played) !== null && _a !== void 0 ? _a : false;
            }
            else {
                this.manager.animationManager.base.attachToElement(element, toElement, insertBefore);
                let result = null;
                const fadeIn = (_b = settings.fadeIn) !== null && _b !== void 0 ? _b : !settings.fromStock;
                if (fadeIn) {
                    result = yield this.manager.animationManager.fadeIn(element, fromElement, settings);
                }
                else {
                    result = yield this.manager.animationManager.slideIn(element, fromElement, settings);
                }
                return (_c = result === null || result === void 0 ? void 0 : result.played) !== null && _c !== void 0 ? _c : false;
            }
        });
    }
    getNewDieIndex(die) {
        if (this.sort) {
            const otherDice = this.getDice();
            for (let i = 0; i < otherDice.length; i++) {
                const otherDie = otherDice[i];
                if (this.sort(die, otherDie) < 0) {
                    return i;
                }
            }
            return otherDice.length;
        }
        else {
            return undefined;
        }
    }
    addDieElementToParent(dieElement, settings) {
        var _a;
        const parent = (_a = settings === null || settings === void 0 ? void 0 : settings.forceToElement) !== null && _a !== void 0 ? _a : this.element;
        if ((settings === null || settings === void 0 ? void 0 : settings.index) === null || (settings === null || settings === void 0 ? void 0 : settings.index) === undefined || !parent.children.length || (settings === null || settings === void 0 ? void 0 : settings.index) >= parent.children.length) {
            parent.appendChild(dieElement);
        }
        else {
            parent.insertBefore(dieElement, parent.children[settings.index]);
        }
    }
    /**
     * Add an array of dice to the stock.
     *
     * @param dice the dice to add
     * @param settings a `AddDiceettings` object
     * @param shift if number, the number of milliseconds between each die. if true, chain animations
     */
    addDice(dice_1, settings_1) {
        return __awaiter(this, arguments, void 0, function* (dice, settings, shift = false) {
            const diceArray = diceInputToArray(dice);
            if (!this.manager.animationManager.animationsActive()) {
                shift = false;
            }
            let promises = [];
            if (shift === true) {
                if (diceArray.length) {
                    const result = yield this.addDie(diceArray[0], settings);
                    const others = yield this.addDice(diceArray.slice(1), settings, shift);
                    return result || others;
                }
            }
            else if (typeof shift === 'number') {
                for (let i = 0; i < diceArray.length; i++) {
                    promises.push(new Promise(resolve => {
                        setTimeout(() => this.addDie(diceArray[i], settings).then(result => resolve(result)), i * shift);
                    }));
                }
            }
            else {
                promises = diceArray.map(die => this.addDie(die, settings));
            }
            const results = yield Promise.all(promises);
            return results.some(result => result);
        });
    }
    /**
     * Remove a die from the stock.
     *
     * @param die die die to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDie(die, settings) {
        if (this.contains(die) && this.element.contains(this.getDieElement(die))) {
            this.manager.removeDie(die, settings);
        }
        this.dieRemoved(die);
    }
    /**
     * Notify the stock that a die is removed.
     *
     * @param die the die to remove
     */
    dieRemoved(die) {
        const index = this.dice.findIndex(c => this.manager.getId(c) == this.manager.getId(die));
        if (index !== -1) {
            this.dice.splice(index, 1);
        }
        if (this.selectedDice.find(c => this.manager.getId(c) == this.manager.getId(die))) {
            this.unselectDie(die);
        }
    }
    /**
     * Remove a set of dice from the stock.
     *
     * @param dice the dice to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDice(dice, settings) {
        diceInputToArray(dice).forEach(die => this.removeDie(die, settings));
    }
    /**
     * Remove all dice from the stock.
     */
    removeAll(settings) {
        const dice = this.getDice(); // use a copy of the array as we iterate and modify it at the same time
        dice.forEach(die => this.removeDie(die, settings));
    }
    /**
     * Set if the stock is selectable, and if yes if it can be multiple.
     * If set to 'none', it will unselect all selected dice.
     *
     * @param selectionMode the selection mode
     * @param selectableDice the selectable dice (all if unset). Calls `setSelectableDice` method
     */
    setSelectionMode(selectionMode, selectableDice) {
        if (selectionMode !== this.selectionMode) {
            this.unselectAll(true);
        }
        this.dice.forEach(die => this.setSelectableDie(die, selectionMode != 'none'));
        this.element.classList.toggle('bga-dice_selectable-stock', selectionMode != 'none');
        this.selectionMode = selectionMode;
        if (selectionMode === 'none') {
            this.getDice().forEach(die => this.removeSelectionClasses(die));
        }
        else {
            this.setSelectableDice(selectableDice !== null && selectableDice !== void 0 ? selectableDice : this.getDice());
        }
    }
    setSelectableDie(die, selectable) {
        if (this.selectionMode === 'none') {
            return;
        }
        const element = this.getDieElement(die);
        const selectableDiceClass = this.getSelectableDieClass();
        const unselectableDiceClass = this.getUnselectableDieClass();
        if (selectableDiceClass) {
            element.classList.toggle(selectableDiceClass, selectable);
        }
        if (unselectableDiceClass) {
            element.classList.toggle(unselectableDiceClass, !selectable);
        }
        const index = this.selectableDice.findIndex(c => this.manager.getId(c) === this.manager.getId(die));
        if (selectable) {
            if (index === -1) {
                this.selectableDice.push(die);
            }
        }
        else {
            if (index !== -1) {
                this.selectableDice.splice(index, 1);
            }
            if (this.isSelected(die)) {
                this.unselectDie(die, true);
            }
        }
    }
    /**
     * Set the selectable class for each die.
     *
     * @param selectableDice the selectable dice. If unset, all dice are marked selectable. Default unset.
     */
    setSelectableDice(selectableDice) {
        if (this.selectionMode === 'none') {
            return;
        }
        this.selectableDice = [];
        const selectableDiceIds = (selectableDice ? diceInputToArray(selectableDice) : this.getDice()).map(die => this.manager.getId(die));
        this.dice.forEach(die => this.setSelectableDie(die, selectableDiceIds.includes(this.manager.getId(die))));
    }
    /**
     * Set selected state to a die.
     *
     * @param die the die to select
     */
    selectDie(die, silent = false) {
        var _a;
        if (this.selectionMode == 'none') {
            return;
        }
        const element = this.getDieElement(die);
        if (!element || !this.selectableDice.some(c => this.manager.getId(c) == this.manager.getId(die))) {
            return;
        }
        if (this.selectionMode === 'single') {
            this.dice.filter(c => this.manager.getId(c) != this.manager.getId(die)).forEach(c => this.unselectDie(c, true));
        }
        const selectedDiceClass = this.getSelectedDieClass();
        element.classList.add(selectedDiceClass);
        this.selectedDice.push(die);
        if (!silent) {
            (_a = this.onSelectionChange) === null || _a === void 0 ? void 0 : _a.call(this, this.selectedDice.slice(), die);
        }
    }
    /**
     * Set unselected state to a die.
     *
     * @param die the die to unselect
     */
    unselectDie(die, silent = false) {
        var _a;
        const element = this.getDieElement(die);
        // const selectable = this.selectableDice.some(c => this.manager.getId(c) == this.manager.getId(die));
        const selectedDiceClass = this.getSelectedDieClass();
        element.classList.remove(selectedDiceClass);
        const index = this.selectedDice.findIndex(c => this.manager.getId(c) == this.manager.getId(die));
        if (index !== -1) {
            this.selectedDice.splice(index, 1);
        }
        if (!silent) {
            (_a = this.onSelectionChange) === null || _a === void 0 ? void 0 : _a.call(this, this.selectedDice.slice(), die);
        }
    }
    /**
     * Select all dice
     */
    selectAll(silent = false) {
        var _a;
        if (this.selectionMode == 'none') {
            return;
        }
        this.dice.forEach(c => this.selectDie(c, true));
        if (!silent) {
            (_a = this.onSelectionChange) === null || _a === void 0 ? void 0 : _a.call(this, this.selectedDice.slice(), null);
        }
    }
    /**
     * Unelect all dice
     */
    unselectAll(silent = false) {
        var _a;
        const dice = this.getDice(); // use a copy of the array as we iterate and modify it at the same time
        dice.forEach(c => this.unselectDie(c, true));
        if (!silent) {
            (_a = this.onSelectionChange) === null || _a === void 0 ? void 0 : _a.call(this, this.selectedDice.slice(), null);
        }
    }
    bindClick() {
        var _a;
        (_a = this.element) === null || _a === void 0 ? void 0 : _a.addEventListener('click', event => {
            const dieDiv = event.target.closest('.bga-dice_die');
            if (!dieDiv) {
                return;
            }
            const die = this.dice.find(c => this.manager.getDieElementId(c) == dieDiv.id);
            if (!die) {
                return;
            }
            this.dieClick(die);
        });
    }
    dieClick(die) {
        var _a;
        if (this.selectionMode != 'none') {
            const alreadySelected = this.selectedDice.some(c => this.manager.getId(c) == this.manager.getId(die));
            if (alreadySelected) {
                this.unselectDie(die);
            }
            else {
                this.selectDie(die);
            }
        }
        const cardClickEventFilter = this.getCardClickEventFilter();
        let notifyEvent = false;
        if (cardClickEventFilter === 'all') {
            notifyEvent = true;
        }
        else if (cardClickEventFilter === 'stock-selectable') {
            notifyEvent = this.selectionMode != 'none';
        }
        else if (cardClickEventFilter === 'selectable') {
            notifyEvent = this.selectableDice.some(c => this.manager.getId(c) == this.manager.getId(die));
        }
        if (notifyEvent) {
            (_a = this.onDieClick) === null || _a === void 0 ? void 0 : _a.call(this, die);
        }
    }
    /**
     * @returns the filtering to apply on die click events. Use setting from manager if unset.
     */
    getCardClickEventFilter() {
        var _a, _b;
        return (_b = (_a = this.settings) === null || _a === void 0 ? void 0 : _a.dieClickEventFilter) !== null && _b !== void 0 ? _b : this.manager.getDieClickEventFilter();
    }
    /**
     * @returns the perspective for this stock.
     */
    getPerspective() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.perspective) === undefined ? this.manager.getPerspective() : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.perspective;
    }
    /**
     * @returns the class to apply to selectable dice. Use class from manager is unset.
     */
    getSelectableDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.selectableDieClass) === undefined ? this.manager.getSelectableDieClass() : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.selectableDieClass;
    }
    /**
     * @returns the class to apply to selectable dice. Use class from manager is unset.
     */
    getUnselectableDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.unselectableDieClass) === undefined ? this.manager.getUnselectableDieClass() : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.unselectableDieClass;
    }
    /**
     * @returns the class to apply to selected dice. Use class from manager is unset.
     */
    getSelectedDieClass() {
        var _a, _b;
        return ((_a = this.settings) === null || _a === void 0 ? void 0 : _a.selectedDieClass) === undefined ? this.manager.getSelectedDieClass() : (_b = this.settings) === null || _b === void 0 ? void 0 : _b.selectedDieClass;
    }
    removeSelectionClasses(die) {
        this.removeSelectionClassesFromElement(this.getDieElement(die));
    }
    removeSelectionClassesFromElement(dieElement) {
        const selectableDiceClass = this.getSelectableDieClass();
        const unselectableDiceClass = this.getUnselectableDieClass();
        const selectedDiceClass = this.getSelectedDieClass();
        dieElement.classList.remove(selectableDiceClass, unselectableDiceClass, selectedDiceClass);
    }
    getRand(min, max) {
        return Math.floor(Math.random() * ((max + 1) - min) + min);
    }
    getRollAnimation(element_1, duration_1) {
        return __awaiter(this, arguments, void 0, function* (element, duration, deltaYFrom = 0, deltaYTo = 0, moveHorizontally = true, angle = 0) {
            const size = this.manager.getSize();
            const distance = deltaYTo - deltaYFrom;
            const horizontalMargin = () => moveHorizontally ? this.getRand(-size / 4, size / 4) : 0;
            yield element.animate([
                { transform: `translate(${horizontalMargin()}px, ${deltaYFrom}px) translateZ(${size * 4}px) rotate(${angle}deg)` },
                { transform: `translate(${horizontalMargin()}px, ${deltaYFrom + distance * 0.2}px) rotate(${angle}deg)` },
                { transform: `translate(${horizontalMargin()}px, ${deltaYFrom + distance * 0.4}px) translateZ(${size * 3}px) rotate(${angle}deg)` },
                { transform: `translate(${horizontalMargin()}px, ${deltaYFrom + distance * 0.6}px) rotate(${angle}deg)` },
                { transform: `translate(${horizontalMargin()}px, ${deltaYFrom + distance * 0.8}px) translateZ(${size * 2}px) rotate(${angle}deg)` },
                { transform: `translate(0px, ${deltaYTo}px) rotate(${angle}deg)` },
            ], duration).finished;
        });
    }
    addRollEffectToDieElement(die, element, effect, duration) {
        return __awaiter(this, void 0, void 0, function* () {
            const size = this.manager.getSize();
            switch (effect) {
                case 'rollIn':
                    yield this.getRollAnimation(element, duration, -size * 5, 0);
                    break;
                case 'rollOutPauseAndBack':
                    const angle = this.getRand(-45, 45);
                    const distance = this.getRand(size * 4, size * 6);
                    yield this.getRollAnimation(element, duration, 0, distance, true, angle);
                    yield element.animate([
                        { transform: `translate(0px, ${distance}px) rotate(${angle}deg)` },
                        { transform: `translate(0px, ${distance}px) rotate(${angle}deg)` },
                    ], duration / 3).finished;
                    yield element.animate([
                        { transform: `translate(0px, ${distance}px) rotate(${angle}deg)` },
                        { transform: `translate(0px, 0px)` },
                    ], duration / 3).finished;
                    break;
                case 'turn':
                    yield this.manager.animationManager.base.wait(duration);
                    break;
            }
        });
    }
    /**
     * Start the rolling dice animation.
     * Usually, you want to change the dice faces just before rolling them.
     */
    rollDice(dice, settings) {
        diceInputToArray(dice).forEach(die => this.rollDie(die, settings));
    }
    /**
     * Start the rolling die animation.
     * Usually, you want to change the die face just before rolling it.
     */
    rollDie(die, settings) {
        return __awaiter(this, void 0, void 0, function* () {
            var _a, _b;
            const div = this.getDieElement(die);
            const faces = div.querySelector('.bga-dice_die-faces');
            let initialTransform = window.getComputedStyle(faces).transform;
            faces.dataset.visibleFace = `${this.manager.getDieFace(die)}`;
            const rollEffect = (_a = settings === null || settings === void 0 ? void 0 : settings.effect) !== null && _a !== void 0 ? _a : 'rollOutPauseAndBack';
            const animate = this.manager.animationManager.animationsActive() && rollEffect !== 'none';
            if (animate) {
                let duration = (_b = settings === null || settings === void 0 ? void 0 : settings.duration) !== null && _b !== void 0 ? _b : 1000;
                if (Array.isArray(duration)) {
                    duration = this.getRand(duration[0], duration[1]);
                }
                const getRandDeg = () => this.getRand(540, 720);
                if (rollEffect !== 'turn') {
                    initialTransform = `rotateX(${getRandDeg()}deg) rotateY(${getRandDeg()}deg) rotateZ(${getRandDeg()}deg)`;
                }
                yield Promise.all([
                    // dice movement animation (slide with bumps)
                    this.addRollEffectToDieElement(die, div, rollEffect, duration),
                    // dice roll animation (roll on itself)
                    faces.animate([
                        { transform: initialTransform },
                        { transform: `` },
                    ], duration).finished,
                ]);
            }
        });
    }
    /**
     * Returns if a die should be placed on this stock (with the autoPlace setting).
     */
    shouldPlaceDie(die) {
        var _a, _b, _c;
        return (_c = (_b = (_a = this.settings).autoPlace) === null || _b === void 0 ? void 0 : _b.call(_a, die)) !== null && _c !== void 0 ? _c : false;
    }
}

/**
 * A basic stock for a list of dice, based on flex.
 */
class LineStock extends DiceStock {
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     * @param settings a `LineStockSettings` object
     */
    constructor(manager, element, settings) {
        var _a, _b, _c, _d;
        super(manager, element, settings);
        this.manager = manager;
        this.element = element;
        element.classList.add('bga-dice_line-stock');
        element.dataset.center = ((_a = settings === null || settings === void 0 ? void 0 : settings.center) !== null && _a !== void 0 ? _a : true).toString();
        element.style.setProperty('--wrap', (_b = settings === null || settings === void 0 ? void 0 : settings.wrap) !== null && _b !== void 0 ? _b : 'wrap');
        element.style.setProperty('--direction', (_c = settings === null || settings === void 0 ? void 0 : settings.direction) !== null && _c !== void 0 ? _c : 'row');
        element.style.setProperty('--gap', (_d = settings === null || settings === void 0 ? void 0 : settings.gap) !== null && _d !== void 0 ? _d : '8px');
    }
}

/**
 * A stock with manually placed dice
 */
class ManualPositionStock extends DiceStock {
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager, element, settings, updateDisplay) {
        super(manager, element, settings);
        this.manager = manager;
        this.element = element;
        this.updateDisplay = updateDisplay;
        element.classList.add('bga-dice_manual-position-stock');
    }
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDiceettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die, settings) {
        const promise = super.addDie(die, settings);
        this.updateDisplay(this.element, this.getDice(), die, this);
        return promise;
    }
    dieRemoved(die) {
        super.dieRemoved(die);
        this.updateDisplay(this.element, this.getDice(), die, this);
    }
}

/**
 * A stock with fixed slots (some can be empty)
 */
class SlotStock extends LineStock {
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     * @param settings a `SlotStockSettings` object
     */
    constructor(manager, element, settings) {
        var _a, _b;
        super(manager, element, settings);
        this.manager = manager;
        this.element = element;
        this.slotsIds = [];
        this.slots = [];
        element.classList.add('bga-dice_slot-stock');
        this.mapDieToSlot = settings.mapDieToSlot;
        this.slotsIds = (_a = settings.slotsIds) !== null && _a !== void 0 ? _a : [];
        this.slotClasses = (_b = settings.slotClasses) !== null && _b !== void 0 ? _b : [];
        this.slotsIds.forEach(slotId => {
            this.createSlot(slotId);
        });
    }
    createSlot(slotId) {
        this.slots[slotId] = document.createElement("div");
        this.slots[slotId].dataset.slotId = slotId;
        this.element.appendChild(this.slots[slotId]);
        this.slots[slotId].classList.add(...['slot', ...this.slotClasses]);
    }
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieToSlotSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die, settings) {
        var _a, _b;
        const slotId = (_a = settings === null || settings === void 0 ? void 0 : settings.slot) !== null && _a !== void 0 ? _a : (_b = this.mapDieToSlot) === null || _b === void 0 ? void 0 : _b.call(this, die);
        if (slotId === undefined) {
            throw new Error(`Impossible to add die to slot : no SlotId. Add slotId to settings or set mapDieToSlot to SlotDie constructor.`);
        }
        if (!this.slots[slotId]) {
            throw new Error(`Impossible to add die to slot "${slotId}" : slot "${slotId}" doesn't exists.`);
        }
        const newSettings = Object.assign(Object.assign({}, settings), { forceToElement: this.slots[slotId] });
        return super.addDie(die, newSettings);
    }
    /**
     * Change the slots ids. Will empty the stock before re-creating the slots.
     *
     * @param slotsIds the new slotsIds. Will replace the old ones.
     */
    setSlotsIds(slotsIds) {
        if (slotsIds.length == this.slotsIds.length && slotsIds.every((slotId, index) => this.slotsIds[index] === slotId)) {
            // no change
            return;
        }
        this.removeAll();
        this.element.innerHTML = '';
        this.slotsIds = slotsIds !== null && slotsIds !== void 0 ? slotsIds : [];
        this.slotsIds.forEach(slotId => {
            this.createSlot(slotId);
        });
    }
    canAddDie(die, settings) {
        var _a, _b;
        if (!this.contains(die)) {
            return true;
        }
        else {
            const currentDicelot = this.getDieElement(die).closest('.slot').dataset.slotId;
            const slotId = (_a = settings === null || settings === void 0 ? void 0 : settings.slot) !== null && _a !== void 0 ? _a : (_b = this.mapDieToSlot) === null || _b === void 0 ? void 0 : _b.call(this, die);
            return currentDicelot != slotId;
        }
    }
    /**
     * Swap dice inside the slot stock.
     *
     * @param dice the dice to swap
     * @param settings for `updateInformations` and `selectable`
     */
    swapDice(dice, settings) {
        const diceArray = diceInputToArray(dice);
        const elements = diceArray.map(die => this.manager.getDieElement(die));
        diceArray.forEach((die, index) => {
            const dieElement = elements[index];
            const dieIndex = this.dice.findIndex(c => this.manager.getId(c) == this.manager.getId(die));
            if (dieIndex !== -1) {
                this.dice.splice(dieIndex, 1, die);
            }
            this.manager.updateDieInformations(die);
            this.removeSelectionClassesFromElement(dieElement);
        });
        const promise = this.manager.animationManager.swap(elements);
        diceArray.forEach((die, index) => {
            promise.then(() => {
                var _a;
                //this.manager.animationManager.base.attachToElement(dieElement, this.slots[slotId]);
                this.setSelectableDie(die, (_a = settings === null || settings === void 0 ? void 0 : settings.selectable) !== null && _a !== void 0 ? _a : true);
            });
        });
        return promise;
    }
}

/**
 * A stock to make dice disappear (to automatically remove disdieed dice, or to represent a bag)
 */
class VoidStock extends DiceStock {
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager, element) {
        super(manager, element);
        this.manager = manager;
        this.element = element;
        element.classList.add('bga-dice_void-stock');
    }
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieToVoidStockSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die, settings) {
        var _a;
        let promise = super.addDie(die, settings);
        // center the element
        const dieElement = this.getDieElement(die);
        const originalLeft = dieElement.style.left;
        const originalTop = dieElement.style.top;
        dieElement.style.left = `${(this.element.clientWidth - dieElement.clientWidth) / 2}px`;
        dieElement.style.top = `${(this.element.clientHeight - dieElement.clientHeight) / 2}px`;
        if (!promise) {
            console.warn(`VoidStock.addDie didn't return a Promise`);
            promise = Promise.resolve(false);
        }
        if ((_a = settings === null || settings === void 0 ? void 0 : settings.remove) !== null && _a !== void 0 ? _a : true) {
            return promise.then(result => {
                this.removeDie(die);
                return result;
            });
        }
        else {
            dieElement.style.left = originalLeft;
            dieElement.style.top = originalTop;
            return promise;
        }
    }
    /**
     * Add an array or keyed object of dice to the stock.
     *
     * @param dice the dice to add
     * @param settings a `AddDieToVoidStockSettings` object
     * @param shift if number, the number of milliseconds between each die. if true, chain animations
     */
    addDice(dice_1, settings_1) {
        const _super = Object.create(null, {
            addDice: { get: () => super.addDice }
        });
        return __awaiter(this, arguments, void 0, function* (dice, settings, shift = false) {
            return _super.addDice.call(this, dice, settings, shift);
        });
    }
}

const BgaDice = {
    Manager: DiceManager,
    sort,
    DiceStock,
    LineStock,
    ManualPositionStock,
    SlotStock,
    VoidStock,
};

export { BgaDice, DiceStock, LineStock, DiceManager as Manager, ManualPositionStock, SlotStock, VoidStock, sort };
