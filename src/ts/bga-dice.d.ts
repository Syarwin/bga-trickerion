type DiceInput<T> = T[] | Record<string | number, T>;

type SortFunction<T> = (a: T, b: T) => number;
declare function sort<T>(...sortedFields: string[]): SortFunction<T>;

declare type SlideAnimationSettings = any;

interface DieStockSettings<T> {
    /**
     * Indicate the die sorting (unset means no sorting, new dice will be added at the end).
     * For example, use `sort: sortFunction('type', '-type_arg')` to sort by type then type_arg (in reversed order if prefixed with `-`).
     * Be sure you typed the values correctly! Else '11' will be before '2'.
     */
    sort?: SortFunction<T>;
    /**
     * Perspective effect on Stock elements. Default 1000px. Can be overriden on each stock.
     */
    perspective?: number | null;
    /**
     * The filter on die click event. Use setting from manager is unset.
     */
    dieClickEventFilter?: DieClickEventFilter;
    /**
     * The class to apply to selectable dice. Use class from manager is unset.
     */
    selectableDieClass?: string | null;
    /**
     * The class to apply to selectable dice. Use class from manager is unset.
     */
    unselectableDieClass?: string | null;
    /**
     * The class to apply to selected dice. Use class from manager is unset.
     */
    selectedDieClass?: string | null;
    /**
     * Say if a given card should be placed on this stock, based on card properties.
     * For example, every card with location === 'hand' and location_arg == this.player_id should go to the current player hand.
     * If unset, all cards on this stock must be added manually on this stock.
     *
     * @param card the card to place on a stock
     * @returns true if the card should be placed on this stock.
     */
    autoPlace?: (card: T) => boolean;
}
interface AddDieSettings extends SlideAnimationSettings {
    /**
     * The stock to take the die. It will automatically remove the die from the other stock.
     */
    fromStock?: DiceStock<any>;
    /**
     * The element to move the die from.
     */
    fromElement?: HTMLElement;
    forceToElement?: HTMLElement;
    /**
     * Force die position. Default to end of list. Do not use if sort is defined, as it will override it.
     */
    index?: number;
    /**
     * Set if the card is selectable. Default is true, but will be ignored if the stock is not selectable.
     */
    selectable?: boolean;
    /**
     * Indicates if we add a fade in effect when adding card (if it comes from an invisible or abstract element).
     */
    fadeIn?: boolean;
}
interface RemoveDieSettings {
    slideTo?: HTMLElement;
    fadeOut?: boolean;
}
interface RollDieSettings {
    /**
     * Set the dice roll effect. Default 'rollOutPauseAndBack';
     */
    effect?: DiceRollEffect;
    /**
     * Duration. A number (if fixed), or an array of 2, and it will be a random value between the 2 values.
     * Default 1000.
     */
    duration: number | number[];
}
type DiceSelectionMode = 'none' | 'single' | 'multiple';
type DiceRollEffect = 'rollIn' | 'rollOutPauseAndBack' | 'turn' | 'none';
/**
 * The abstract stock. It shouldn't be used directly, use stocks that extends it.
 */
declare class DiceStock<T> {
    protected manager: DiceManager<T>;
    protected element: HTMLElement;
    private settings?;
    protected dice: T[];
    protected selectableDice: T[];
    protected selectedDice: T[];
    protected selectionMode: DiceSelectionMode;
    protected sort?: SortFunction<T>;
    /**
     * Called when selection change. Returns the selection.
     *
     * selection: the selected dice of the stock
     * lastChange: the last change on selection die (can be selected or unselected)
     */
    onSelectionChange?: (selection: T[], lastChange: T | null) => void;
    /**
     * Called when selection change. Returns the clicked die.
     *
     * die: the clicked die (can be selected or unselected)
     */
    onDieClick?: (die: T) => void;
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager: DiceManager<T>, element: HTMLElement, settings?: DieStockSettings<T>);
    /**
     * @returns the dice on the stock
     */
    getDice(): T[];
    /**
     * @returns the HTML element used by this stock
     */
    getElement(): HTMLElement;
    /**
     * @returns if the stock is empty
     */
    isEmpty(): boolean;
    /**
     * @returns the selected dice
     */
    getSelection(): T[];
    /**
     * @returns if the card is selectable
     */
    isSelectable(die: T): boolean;
    /**
     * @returns the selected dice
     */
    isSelected(die: T): boolean;
    /**
     * @param die a die
     * @returns if the die is present in the stock
     */
    contains(die: T): boolean;
    /**
     * @param die a die in the stock
     * @returns the HTML element generated for the die
     */
    getDieElement(die: T): HTMLElement;
    /**
     * Checks if the die can be added. By default, only if it isn't already present in the stock.
     *
     * @param die the die to add
     * @param settings the addDie settings
     * @returns if the die can be added
     */
    protected canAddDie(die: T, settings?: AddDieSettings): boolean;
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die: T, settings?: AddDieSettings): Promise<boolean>;
    protected addExistingDieElement(die: T, dieElement: HTMLElement, settings?: AddDieSettings): Promise<boolean>;
    protected addUnexistingDieElement(die: T, settings?: AddDieSettings): Promise<boolean>;
    /**
     * @param element The element to animate. The element is added to the destination stock before the animation starts.
     * @param toElement The HTMLElement to attach the die to.
     */
    protected animationFromElement(die: T, element: HTMLElement, fromElement: HTMLElement | null | undefined, toElement: HTMLElement, insertBefore: HTMLElement | null | undefined, settings: AddDieSettings): Promise<boolean>;
    protected getNewDieIndex(die: T): number | undefined;
    protected addDieElementToParent(dieElement: HTMLElement, settings?: AddDieSettings): void;
    /**
     * Add an array of dice to the stock.
     *
     * @param dice the dice to add
     * @param settings a `AddDiceettings` object
     * @param shift if number, the number of milliseconds between each die. if true, chain animations
     */
    addDice(dice: DiceInput<T>, settings?: AddDieSettings, shift?: number | boolean): Promise<boolean>;
    /**
     * Remove a die from the stock.
     *
     * @param die die die to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDie(die: T, settings?: RemoveDieSettings): void;
    /**
     * Notify the stock that a die is removed.
     *
     * @param die the die to remove
     */
    dieRemoved(die: T): void;
    /**
     * Remove a set of dice from the stock.
     *
     * @param dice the dice to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDice(dice: DiceInput<T>, settings?: RemoveDieSettings): void;
    /**
     * Remove all dice from the stock.
     */
    removeAll(settings?: RemoveDieSettings): void;
    /**
     * Set if the stock is selectable, and if yes if it can be multiple.
     * If set to 'none', it will unselect all selected dice.
     *
     * @param selectionMode the selection mode
     * @param selectableDice the selectable dice (all if unset). Calls `setSelectableDice` method
     */
    setSelectionMode(selectionMode: DiceSelectionMode, selectableDice?: DiceInput<T>): void;
    protected setSelectableDie(die: T, selectable: boolean): void;
    /**
     * Set the selectable class for each die.
     *
     * @param selectableDice the selectable dice. If unset, all dice are marked selectable. Default unset.
     */
    setSelectableDice(selectableDice?: DiceInput<T>): void;
    /**
     * Set selected state to a die.
     *
     * @param die the die to select
     */
    selectDie(die: T, silent?: boolean): void;
    /**
     * Set unselected state to a die.
     *
     * @param die the die to unselect
     */
    unselectDie(die: T, silent?: boolean): void;
    /**
     * Select all dice
     */
    selectAll(silent?: boolean): void;
    /**
     * Unelect all dice
     */
    unselectAll(silent?: boolean): void;
    protected bindClick(): void;
    protected dieClick(die: T): void;
    /**
     * @returns the filtering to apply on die click events. Use setting from manager if unset.
     */
    getCardClickEventFilter(): DieClickEventFilter;
    /**
     * @returns the perspective for this stock.
     */
    private getPerspective;
    /**
     * @returns the class to apply to selectable dice. Use class from manager is unset.
     */
    getSelectableDieClass(): string | null;
    /**
     * @returns the class to apply to selectable dice. Use class from manager is unset.
     */
    getUnselectableDieClass(): string | null;
    /**
     * @returns the class to apply to selected dice. Use class from manager is unset.
     */
    getSelectedDieClass(): string | null;
    removeSelectionClasses(die: T): void;
    removeSelectionClassesFromElement(dieElement: HTMLElement): void;
    protected getRand(min: number, max: number): number;
    protected getRollAnimation(element: Element, duration: number, deltaYFrom?: number, deltaYTo?: number, moveHorizontally?: boolean, angle?: number): Promise<void>;
    protected addRollEffectToDieElement(die: T, element: HTMLElement, effect: DiceRollEffect, duration: number): Promise<void>;
    /**
     * Start the rolling dice animation.
     * Usually, you want to change the dice faces just before rolling them.
     */
    rollDice(dice: DiceInput<T>, settings?: RollDieSettings): void;
    /**
     * Start the rolling die animation.
     * Usually, you want to change the die face just before rolling it.
     */
    rollDie(die: T, settings?: RollDieSettings): Promise<void>;
    /**
     * Returns if a die should be placed on this stock (with the autoPlace setting).
     */
    shouldPlaceDie(die: T): boolean;
}

type AnimationManager = any;
/**
 * selectable: only send die click event when the die is selectable.
 * stock-selectable: only send die click event when the stock is selectable (but the die might be disabled).
 * all: send die click events even if the stock is not selectable.
 */
type DieClickEventFilter = 'selectable' | 'stock-selectable' | 'all';
interface AutoPlaceSettings<T> {
    /**
     * The add dice settings, for example if you want to set an animation from an invisible point (can be the player mini panel)
     * Will only be called if the die match a stock with the "autoPlace" setting.
     *
     * @param die the die to add
     * @returns the settings to add the die. `{ fromElement: originElement }` will add a slide in animation
     */
    addSettings?: (die: T) => AddDieSettings | undefined;
    /**
     * The remove settings, when a die does not match any stock with the "autoPlace" setting.
     *
     * @param die the die to remove
     * @returns the settings to remove the die. `{}` will simply remove the die from it's current stock without any error. `{ slideTo: destinationElement }` will add an animation before removing the die.
     */
    removeSettings?: (die: T) => RemoveDieSettings | undefined;
}
interface DiceManagerSettings<T> {
    /**
     * The type of dice, if you game has multiple dice types (each dice manager should have a different type).
     * Default `${yourgamename}-dice`.
     *
     * The die element will have this type as a class, and each face will have the class `${type}-face-${number}`.
     */
    type?: string;
    /**
     * The number of faces of the die (default 6).
     */
    faces?: number;
    /**
     * The size of the die, in px (default 50).
     */
    size?: number;
    /**
     * The border radius, in % (default 5% for 6-faces, else 0%).
     */
    borderRadius?: number;
    /**
     * The filter on die click event. Default 'selectable'.
     */
    dieClickEventFilter?: DieClickEventFilter;
    /**
     * Define the id that will be set to each die div. It must return a unique id for each different die, so it's often linked to die id.
     *
     * Default: the id will be set to `die.id`.
     *
     * @param die the die informations
     * @return the id for a die
     */
    getId?: (die: T) => string | number;
    /**
     * Allow to populate the main div of the die. You can set classes or dataset, if it's informations shared by all faces.
     *
     * @param die the die informations
     * @param element the die main Div element
     */
    setupDieDiv?: (die: T, element: HTMLDivElement) => void;
    /**
     * Allow to populate a face div of the die. You can set classes or dataset to show the correct die face.
     *
     * @param die the die informations
     * @param element the die face Div element
     * @param face the face number (1-indexed)
     */
    setupFaceDiv?: (die: T, element: HTMLDivElement, face: number) => void;
    /**
     * Return the die face.
     * Default: the face will be set to `die.face`.
     *
     * @param die the die informations
     * @return the die face
     */
    getDieFace?: (die: T) => number;
    /**
     * The animation manager used in the game.
     */
    animationManager: AnimationManager;
    /**
     * Perspective effect on Stock elements. Default 1000px. Can be overriden on each stock.
     */
    perspective?: number | null;
    /**
     * The class to apply to selectable dice. Default 'bga-dice_selectable-die'.
     */
    selectableDieClass?: string | null;
    /**
     * The class to apply to selectable dice. Default 'bga-dice_disabled-die'.
     */
    unselectableDieClass?: string | null;
    /**
     * The class to apply to selected dice. Default 'bga-dice_selected-die'.
     */
    selectedDieClass?: string | null;
    /**
     * The settings when using placeDie/placeDice to automatically place some dice in the matching stock
     */
    autoPlace?: AutoPlaceSettings<T>;
}
declare class DiceManager<T> {
    private settings;
    animationManager: AnimationManager;
    private stocks;
    /**
     * @param settings a `DiceManagerSettings` object
     */
    constructor(settings: DiceManagerSettings<T>);
    addStock(stock: DiceStock<T>): void;
    getFaces(): number;
    getSize(): number;
    getBorderRadius(): number;
    /**
     * @returns the filtering to apply on card click events. Default 'selectable'.
     */
    getDieClickEventFilter(): DieClickEventFilter;
    /**
     * @param die the die informations
     * @return the id for a die
     */
    getId(die: T): string | number;
    /**
     * @param die the die informations
     * @return the id for a die element
     */
    getDieElementId(die: T): string;
    /**
     *
     * @returns the type of the dice, either set in the settings or by using a default name if there is only 1 type.
     */
    getType(): string;
    /**
     * Return the die face.
     * Default: the face will be set to `die.face`.
     *
     * @param die the die informations
     * @return the die face
     */
    getDieFace(die: T): number;
    createDieElement(die: T): HTMLDivElement;
    /**
     * @param die the die informations
     * @return the HTML element of an existing die
     */
    getDieElement(die: T): HTMLElement;
    /**
     * Remove a die.
     *
     * @param die the die to remove
     * @param settings a `RemoveDieSettings` object
     */
    removeDie(die: T, settings?: RemoveDieSettings): Promise<boolean>;
    /**
     * Returns the stock containing the die.
     *
     * @param die the die informations
     * @return the stock containing the die
     */
    getDieStock(die: T): DiceStock<T>;
    /**
     * Update the die informations. Used when a change visible face.
     *
     * @param die the die informations
     */
    updateDieInformations(die: T, updateData?: boolean): void;
    /**
     * @returns the default perspective for all stocks.
     */
    getPerspective(): number | null;
    /**
     * @returns the class to apply to selectable dice. Default 'bga-dice_selectable-die'.
     */
    getSelectableDieClass(): string | null;
    /**
     * @returns the class to apply to selectable dice. Default 'bga-dice_disabled-die'.
     */
    getUnselectableDieClass(): string | null;
    /**
     * @returns the class to apply to selected dice. Default 'bga-dice_selected-die'.
     */
    getSelectedDieClass(): string | null;
    /**
     * Place a die based on the autoPlace settings of each stock.
     */
    placeDie(die: T): Promise<boolean>;
    /**
     * Place some dice based on the autoPlace settings of each stock.
     */
    placeDice(dice: DiceInput<T>): Promise<Promise<boolean>[]>;
}

interface LineStockSettings<T> extends DieStockSettings<T> {
    /**
     * Indicate if the line should wrap when needed (default wrap)
     */
    wrap?: 'wrap' | 'nowrap';
    /**
     * Indicate the line direction (default row)
     */
    direction?: 'row' | 'column';
    /**
     * indicate if the line should be centered (default yes)
     */
    center?: boolean;
    /**
    * CSS to set the gap between dice. '8px' if unset.
    */
    gap?: string;
}
/**
 * A basic stock for a list of dice, based on flex.
 */
declare class LineStock<T> extends DiceStock<T> {
    protected manager: DiceManager<T>;
    protected element: HTMLElement;
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     * @param settings a `LineStockSettings` object
     */
    constructor(manager: DiceManager<T>, element: HTMLElement, settings?: LineStockSettings<T>);
}

/**
 * A stock with manually placed dice
 */
declare class ManualPositionStock<T> extends DiceStock<T> {
    protected manager: DiceManager<T>;
    protected element: HTMLElement;
    protected updateDisplay: (element: HTMLElement, dice: T[], lastDie: T, stock: ManualPositionStock<T>) => any;
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager: DiceManager<T>, element: HTMLElement, settings: DieStockSettings<T>, updateDisplay: (element: HTMLElement, dice: T[], lastDie: T, stock: ManualPositionStock<T>) => any);
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDiceettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die: T, settings?: AddDieSettings): Promise<boolean>;
    dieRemoved(die: T): void;
}

interface SlotStockSettings<T> extends LineStockSettings<T> {
    /**
     * The ids for the slots (can be number or string)
     */
    slotsIds: SlotId[];
    /**
     * The classes to apply to each slot
     */
    slotClasses?: string[];
    /**
     * How to place the die on a slot automatically
     */
    mapDieToSlot?: (die: T) => SlotId;
}
type SlotId = number | string;
interface AddDieToSlotSettings extends AddDieSettings {
    /**
     * The slot to place the die on.
     */
    slot?: SlotId;
}
/**
 * A stock with fixed slots (some can be empty)
 */
declare class SlotStock<T> extends LineStock<T> {
    protected manager: DiceManager<T>;
    protected element: HTMLElement;
    protected slotsIds: SlotId[];
    protected slots: HTMLDivElement[];
    protected slotClasses: string[];
    protected mapDieToSlot?: (die: T) => SlotId;
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     * @param settings a `SlotStockSettings` object
     */
    constructor(manager: DiceManager<T>, element: HTMLElement, settings: SlotStockSettings<T>);
    protected createSlot(slotId: SlotId): void;
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieToSlotSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die: T, settings?: AddDieToSlotSettings): Promise<boolean>;
    /**
     * Change the slots ids. Will empty the stock before re-creating the slots.
     *
     * @param slotsIds the new slotsIds. Will replace the old ones.
     */
    setSlotsIds(slotsIds: SlotId[]): void;
    protected canAddDie(die: T, settings?: AddDieToSlotSettings): boolean;
    /**
     * Swap dice inside the slot stock.
     *
     * @param dice the dice to swap
     * @param settings for `updateInformations` and `selectable`
     */
    swapDice(dice: DiceInput<T>, settings?: AddDieSettings): any;
}

interface AddDieToVoidStockSettings extends AddDieSettings {
    /**
     * Removes the die after adding.
     * Set to false if you want to add the die to the void to stock to animate it to another stock just after.
     * Default true
     */
    remove?: boolean;
}
/**
 * A stock to make dice disappear (to automatically remove disdieed dice, or to represent a bag)
 */
declare class VoidStock<T> extends DiceStock<T> {
    protected manager: DiceManager<T>;
    protected element: HTMLElement;
    /**
     * @param manager the die manager
     * @param element the stock element (should be an empty HTML Element)
     */
    constructor(manager: DiceManager<T>, element: HTMLElement);
    /**
     * Add a die to the stock.
     *
     * @param die the die to add
     * @param settings a `AddDieToVoidStockSettings` object
     * @returns the promise when the animation is done (true if it was animated, false if it wasn't)
     */
    addDie(die: T, settings?: AddDieToVoidStockSettings): Promise<boolean>;
    /**
     * Add an array or keyed object of dice to the stock.
     *
     * @param dice the dice to add
     * @param settings a `AddDieToVoidStockSettings` object
     * @param shift if number, the number of milliseconds between each die. if true, chain animations
     */
    addDice(dice: DiceInput<T>, settings?: AddDieToVoidStockSettings, shift?: number | boolean): Promise<boolean>;
}

declare const BgaDice: {
    Manager: typeof DiceManager;
    sort: typeof sort;
    DiceStock: typeof DiceStock;
    LineStock: typeof LineStock;
    ManualPositionStock: typeof ManualPositionStock;
    SlotStock: typeof SlotStock;
    VoidStock: typeof VoidStock;
};

export { BgaDice, DiceStock, LineStock, DiceManager as Manager, ManualPositionStock, SlotStock, VoidStock, sort };
export type { DiceInput };
