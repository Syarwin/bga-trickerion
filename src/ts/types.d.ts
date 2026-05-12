interface TrickerionPlayer extends ExtendedPlayer {
    shards: number;
    coins: number;
    initiative: number;
    color_name: string;
}

/*
 ██████╗  █████╗ ███╗   ███╗███████╗██████╗  █████╗ ████████╗ █████╗ ███████╗
██╔════╝ ██╔══██╗████╗ ████║██╔════╝██╔══██╗██╔══██╗╚══██╔══╝██╔══██╗██╔════╝
██║  ███╗███████║██╔████╔██║█████╗  ██║  ██║███████║   ██║   ███████║███████╗
██║   ██║██╔══██║██║╚██╔╝██║██╔══╝  ██║  ██║██╔══██║   ██║   ██╔══██║╚════██║
╚██████╔╝██║  ██║██║ ╚═╝ ██║███████╗██████╔╝██║  ██║   ██║   ██║  ██║███████║
 ╚═════╝ ╚═╝  ╚═╝╚═╝     ╚═╝╚══════╝╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚═╝  ╚═╝╚══════╝

*/

interface TrickerionGamedatas extends ExtendedGamedatas<TrickerionPlayer> {
    globals: TrickerionGlobals;
    tricks: {
        available: Trick[];
        player: { [playerId: number]: Trick };
    };
    performances: {
        active: Performance[];
        deck: {
            "state": number;
            "theater": string;
        }[];
    };
    assignments:  {
        hand: Assignment[];
        deckRemaining: {
            theater_deck: number;
            downtown_deck: number;
            workshop_deck: number;
            market_row_deck: number;
        };
        discards: {
            theater_discard: Assignment[];
            downtown_discard: Assignment[];
            workshop_discard: Assignment[];
            market_row_discard: Assignment[];
        };
        assigned: {
            my: Assignment[];
            other: {
                [playerId: number]: {
                    revealed: Assignment[];
                    hidden: {
                        location: string;
                        state: number;
                    }[];
                };
            }
        }
    };
    prophecies: {
        pending: Prophecy[];
        active: Prophecy[];
        deckRemaining: number;
        discarded: Prophecy[];
    };
    magicians: {
        available: Magician[];
        player: { [playerId: number]: Magician };
    };
    trickMarkers: {
        available: TrickMarker[];
        prepared: TrickMarker[];
        scheduled: TrickMarker[];
    };
    characters: {
        supply: Character[];
        board: Character[];
        idle: Character[];
        hiredSpecialists: {
            [playerId: number]: CharacterType[];
        }
    },
    components: {
        player: {
            [playerId: number]: Component[];
        }
    }
    posters: {
        all: Poster[];
    }
}

/*
███╗   ███╗ ██████╗ ██████╗ ███████╗██╗     ███████╗
████╗ ████║██╔═══██╗██╔══██╗██╔════╝██║     ██╔════╝
██╔████╔██║██║   ██║██║  ██║█████╗  ██║     ███████╗
██║╚██╔╝██║██║   ██║██║  ██║██╔══╝  ██║     ╚════██║
██║ ╚═╝ ██║╚██████╔╝██████╔╝███████╗███████╗███████║
╚═╝     ╚═╝ ╚═════╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝

*/

interface Poster extends Model<string, PosterLocation> {
}

type PosterLocation = "supply" | "board";

interface Character extends Model<CharacterType, CharacterLocation> {
    onAssistantBoard: boolean;
    actionPoints?: number;
    name?: string;
    specialist?: boolean;
}

type CharacterType = "assistant" | "manager" | "engineer" | "apprentice" | "magician";
type CharacterLocation = "supply"
    | "incoming"
    | "idle-player-board"
    | "idle-manager-board"
    | "idle-assistant-board"
    | "idle-engineer-board"
    | "board-downtown-1"
    | "board-downtown-2"
    | "board-downtown-3"
    | "board-downtown-4"
    | "board-market-row-1"
    | "board-market-row-2"
    | "board-market-row-3"
    | "board-market-row-4"
    | "board-theater-thursday-basic-1"
    | "board-theater-thursday-basic-2"
    | "board-theater-thursday-magician"
    | "board-theater-friday-basic-1"
    | "board-theater-friday-basic-2"
    | "board-theater-friday-magician"
    | "board-theater-saturday-basic-1"
    | "board-theater-saturday-basic-2"
    | "board-theater-saturday-magician"
    | "board-theater-sunday-basic-1"
    | "board-theater-sunday-basic-2"
    | "board-theater-sunday-magician"
    | "board-workshop-1"
    | "board-workshop-2"
    | "board-dark-alley-1"
    | "board-dark-alley-2"
    | "board-dark-alley-3"
    | "board-dark-alley-4"

interface TrickMarker extends Model<string, TrickMarkerLocation> {
    suit: Suit;
    trickId: number;
    slotId: string;
    topTrickCategory: string;
}

type TrickMarkerLocation = "available" | "prepared" | "scheduled";
type Suit = "spades" | "hearts" | "diamonds" | "clubs";

interface Magician extends Model<string, MagicianLocation> {
    favoriteTrickCategory?: TrickCategory;
    name?: string;
    ability?: {
        name: string;
        effect: string;
    };
}

type MagicianLocation = "available" | "player";

interface Prophecy extends Model<string, ProphecyLocation> {
    ability?: string[];
}

type ProphecyLocation = "deck" | "active" | "pending" | "discard";

interface Assignment extends Model<string, AssignmentLocation> {
    boardLocation?: BoardLocation;
    category?: AssignmentCategory;
    name?: string;
    targetAction?: TargetAction;
    abilityText?: string[];
}

type BoardLocation = "theater" | "downtown" | "market-row" | "workshop" | "dark-alley";
type AssignmentCategory = "special" | "permanent";
type TargetAction = "any" 
    | "set-up-trick"
    | "perform"
    | "reschedule"
    | "prepare"
    | "hire-character"
    | "learn-trick"
    | "take-coins"
    | "buy"
    | "order"
    | "quick-order";

type AssignmentLocation = "hand"
    | "theater_deck"
    | "downtown_deck"
    | "workshop_deck"
    | "market_row_deck"
    | "theater_discard"
    | "downtown_discard"
    | "workshop_discard"
    | "market_row_discard"
    | "assigned-faceup"
    | "assigned-facedown"
    | "drawn";

interface Performance extends Model<string, PerformanceLocation> {
    name?: string;
    theater?: PerformanceTheater;
    slots?: { [slotId: string]: Slot };
    bonus?: Yields;
}

type PerformanceTheater = "riverside_theater" | "grand_magorian" | "magnus_pantheon";

interface Slot {
    x: number;
    y: number;
    links: Link[];
}

interface Link {
    direction: LinkDirection;
    shard: boolean
}

type LinkDirection = "up" | "down" | "left" | "right";

interface Yields {
    fame: number;
    coins: number;
    shards: number;
}

interface YieldModifier {
    fame: number;
    coins: number;
}

type PerformanceLocation = "deck" | "active" | "box";

interface Trick extends Model<string, TrickLocation> {
    suit: Suit;
    category?: TrickCategory;
    name?: string;
    componentRequirements?: ComponentType[];
    preparationCost?: number;
    slots?: number;
    level?: 1 | 2 | 3;
    yields?: Yields;
}

type TrickLocation = "available" | "player-board" | "engineer-board" | "box";
type TrickCategory = "spiritual" | "mechanical" | "escape" | "optical";

interface Component extends Model<ComponentType, ComponentLocation> {
    count: number;
    cost?: number;
    name?: string;
}

type ComponentType = "wood" | "glass" | "metal" | "fabric" | "rope" | "petroleum" | "saw" | "animal" | "padlock" | "mirror" | "disguise" | "cog";
type ComponentLocation = "player-board" | "manager-board";

interface TrickerionGlobals {
    marketRow: MarketRow;
    currentTurn: number;
    pickingComponents: Component[];
    dice: Dice;
    locationActions: LocationActions;
    drawAssignmentCardsAction: DrawAssignmentCardsAction;
}

interface DrawAssignmentCardsAction {
    currentCost: number;
}

interface LocationActions {
    locationId: string;
    remainingActionPoints: number;
    oneTimeActionsUsed: string[];
}

interface MarketRow {
    buyArea: { [componentId: number]: ComponentType };
    quickOrder: Component;
    orderArea: { [componentId: number]: ComponentType };
}

interface Dice {
    trick: (TrickCategory | "any" | "not-available")[];
    character: (CharacterType | "not-available")[];
    money: (3 | 4 | 5 | 6 | "not-available")[];
}

/*
███████╗████████╗ █████╗ ████████╗███████╗     █████╗ ██████╗  ██████╗ ███████╗
██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██╔════╝    ██╔══██╗██╔══██╗██╔════╝ ██╔════╝
███████╗   ██║   ███████║   ██║   █████╗      ███████║██████╔╝██║  ███╗███████╗
╚════██║   ██║   ██╔══██║   ██║   ██╔══╝      ██╔══██║██╔══██╗██║   ██║╚════██║
███████║   ██║   ██║  ██║   ██║   ███████╗    ██║  ██║██║  ██║╚██████╔╝███████║
╚══════╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝   ╚══════╝    ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝

*/

interface AdvertiseArgs extends GenericArguments{
    cost: number;
}

interface AssignCharactersArgs extends GenericArguments {
    availableAssignments: Assignment[];
    availableCharacters: Character[];
}

interface BuyComponentsArgs extends GenericArguments {
    remainingActionPoints: number;
    availableComponents:  {
        [componentTypeId: string]: {
            [location: string]: {
                max: number;
                maxWithBargain: number;
                effectiveCost: number;
            };
        }
    }
}

interface ChooseMagicianArgs extends GenericArguments {
    availableMagicians: Magician[];
}

interface DiscardComponentArgs extends GenericArguments {
    availableComponents: Component[];
}

interface PerformanceArgs extends GenericArguments {
    day: Day;
    availablePerformances: Performance[];
}

type Day = "thursday" | "friday" | "saturday" | "sunday";

interface PlaceCharacterArgs extends GenericArguments {
    availableAssignments: {
        assignment: Assignment;
        character: Character;
        possibleLocations: CharacterLocation[];
    }[];
}

interface DiscardTrickArgs extends GenericArguments {
    availableTricks: Trick[];
}

interface DrawAssignmentCardsArgs extends GenericArguments {
    availableLocations: AssignmentLocation[];
    currentDrawCost: number;
    drawnCards: Assignment[];
    canDraw: boolean;
}

interface HireCharacterArgs extends GenericArguments {
    availableCharacterTypes: CharacterType[];
    sourceName: string;
}

interface LearnTrickArgs extends GenericArguments {
    availableTricks: Trick[];
    sourceName: string;
}

interface MakeDieUnavailableArgs extends GenericArguments {
    availableDice: (string | number)[];
    sourceName: string;
}

interface MoveApprenticeArgs extends GenericArguments {
    isSlotAvailable: boolean;
    availableApprentices: Character[];
}

interface MoveComponentsArgs extends GenericArguments {
    availableComponents: Component[];
    usedSlots: Component[];
}

interface MoveTrickArgs extends GenericArguments {
    availableTricks: Trick[];
    engineerBoardTricks: Trick;
}

interface OrderComponentArgs extends GenericArguments {
    availableComponents: ComponentType[];
    availableOrderSlots: number[];
}

interface PickComponentsArgs extends GenericArguments {
    totalValue: number;
    remainingValue: number;
    availableComponents: ComponentType[];
    location: string;
}

interface PlayLocationActionArgs extends GenericArguments {
    availableActions: {
        [actionId: string]: {
            actionPoints?: number;
            minActionPoints?: number;
            singleUse?: boolean;
            state: string;
            shardCost?: number;
            args: any;
            ifCharacterHired?: CharacterType;
        }
    };
    remainingAps: number;
}

interface PrepareTrickArgs extends GenericArguments {
    availableTricks: Trick[];
    sourceName: string;
}

interface QuickOrderComponentArgs extends GenericArguments {
    availableComponents: ComponentType[];
}

interface RerollDieArgs extends GenericArguments {
    availableDice: Dice;
    sourceName: string;
}

interface RescheduleArgs extends GenericArguments {
    availableTrickMarkers: TrickMarker[];
    possiblePerformances: {
        [trickMarkerId: string]: {
            [performanceId: string]: {
                performance: Performance;
                possibleSlots: Slot[];
            }
        }
    };
    sourceName: string;
}

interface SetDieArgs extends GenericArguments {
    availableDice: Dice;
    availableFaces: {
        [dieType: string]: {
            [dieId: number]: (string | number)[];
        }
    };
    sourceName: string;
}

interface SetupTrickArgs extends GenericArguments {
    availablePerformances: Performance[];
    possibleTricksAndSlots: {
        [performanceId: string]: {
            possibleTricks: Trick[];
            possibleSlots: Slot[];
        }
    };
    sourceName: string;
}

interface TakeCoinsArgs extends GenericArguments {
    availableCoins: number[];
    sourceName: string;
}

/*
███╗   ██╗ ██████╗ ████████╗██╗███████╗██╗ ██████╗ █████╗ ████████╗██╗ ██████╗ ███╗   ██╗     █████╗ ██████╗  ██████╗ ███████╗
████╗  ██║██╔═══██╗╚══██╔══╝██║██╔════╝██║██╔════╝██╔══██╗╚══██╔══╝██║██╔═══██╗████╗  ██║    ██╔══██╗██╔══██╗██╔════╝ ██╔════╝
██╔██╗ ██║██║   ██║   ██║   ██║█████╗  ██║██║     ███████║   ██║   ██║██║   ██║██╔██╗ ██║    ███████║██████╔╝██║  ███╗███████╗
██║╚██╗██║██║   ██║   ██║   ██║██╔══╝  ██║██║     ██╔══██║   ██║   ██║██║   ██║██║╚██╗██║    ██╔══██║██╔══██╗██║   ██║╚════██║
██║ ╚████║╚██████╔╝   ██║   ██║██║     ██║╚██████╗██║  ██║   ██║   ██║╚██████╔╝██║ ╚████║    ██║  ██║██║  ██║╚██████╔╝███████║
╚═╝  ╚═══╝ ╚═════╝    ╚═╝   ╚═╝╚═╝     ╚═╝ ╚═════╝╚═╝  ╚═╝   ╚═╝   ╚═╝ ╚═════╝ ╚═╝  ╚═══╝    ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝

*/

interface AssignmentResetArgs {
    player_id: number;
    _private: {
        assignments: Assignment[];
    }
}

interface AssignmentReturnedArgs {
}

interface AssignmentDiscardedArgs {
}

interface AssignmentsDrawnArgs {
    player_id: number;
    count: number;
    _private: {
        assignments: Assignment[];
    }
}

interface AssignmentsDiscardedArgs {
    player_id: number;
    count: number;
    _private: {
        assignments: Assignment[];
        keptAssignments: Assignment[];
    }
}

interface AssignmentAssignedArgs {
    player_id: number;
    characterName: string;
    characterId: number,
    _private: {
        assignment: Assignment;
        assignment_name: string;
    }
}

interface CharacterHiredArgs {
    player_id: number;
    character: Character;
}

interface CharacterReturnedArgs {
    characters: Character[];
}

interface WagesPaidArgs {
    player_id: number;
    characterCount: number;
    totalWages: number;
}

interface CharacterPlacedArgs {
    player_id: number;
    character: Character;
    locationId: CharacterLocation;
    locationName: string;
    actionPoints: number;
}

interface ApprenticeMovedToAssistantArgs {
    player_id: number;
    character: Character;
    attachedAssignment: Assignment;
}

interface ComponentBoughtArgs {
    player_id: number;
    component: Component;
    count: number;
    location: ComponentLocation;
    cost: number;
    bargain: boolean;
}

interface BuyAreaSetArgs {
    components: ComponentType[];
}

interface QuickOrderSetArgs {
    player_id: number;
    componentName: string;
    component: ComponentType;
}

interface ComponentOrderedArgs {
    player_id: number;
    componentName: string;
    slot: number;
    component: ComponentType;
}

interface ComponentArrivedArgs {
    componentId: ComponentType;
    secondComponentId: ComponentType;
    slot: number;
}

interface QuickOrderClearedArgs {
    componentId: ComponentType;
}

interface ComponentMovedArgs {
    player_id: number;
    component: Component;
    secondComponent: Component | null;
}

interface DiceRolledArgs {
    dice: Dice;
}

interface DiceMadeUnavailableArgs {
    player_id: number;
    dieFace: string | number;
    dieId: string;
}

interface DiceRerolledArgs {
    player_id: number;
    dieFace: string | number;
    newDieFace: string | number;
    newDice: Dice;
}

interface DiceSetArgs {
    player_id: number;
    oldDieFace: string | number;
    newDieFace: string | number;
    newDice: Dice;
}

interface PerformanceRemovedArgs {
    performance: Performance;
}

interface PerformancesRotatedArgs {
    performances: Performance[];
}

interface PerformanceRevealedArgs {
    performance: Performance;
}

interface LinkMatchedArgs {
    player_id: number;
    player_id2?: number;
    performanceId: number;
    slotId: number;
    direction: LinkDirection;
    shardMatched: boolean;
}

interface TrickPerformedArgs {
    player_id: number;
    trick: Trick;
    yields: Yields;
    yieldModifier: YieldModifier;
}

interface TrickMarkersReturnedArgs {
    performance: Performance;
    trickMarkers: TrickMarker[];
}

interface InitiativeAdjustedArgs {
    newInitiatives: { [playerId: number]: number };
}

interface ComponentChangedArgs {
    player_id: number;
    count: number;
    component: Component;
}

interface CoinsChangedArgs {
    player_id: number;
    coins: number;
    newValue: number;
}

interface ShardsChangedArgs {
    player_id: number;
    shards: number;
    newValue: number;
}

interface FameChangedArgs {
    player_id: number;
    fame: number;
    newValue: number;
}

interface PostersReturnedArgs {
    posters: Poster[];
}

interface ActiveProphecyDiscardedArgs {
    prophecy: Prophecy;
}

interface ActiveProphecySetArgs {
    prophecy: Prophecy;
}

interface PendingPropheciesRotatedArgs {
    prophecies: Prophecy[];
}

interface NewPendingProphecyRevealedArgs {
    prophecy: Prophecy;
}

interface TrickLearnedArgs {
    player_id: number;
    trick: Trick;
}

interface TrickDiscardedArgs {
    player_id: number;
    trick: Trick;
    markers: TrickMarker[];
}

interface TrickPreparedArgs {
    player_id: number;
    trick: Trick;
    markers: TrickMarker[];
    count: number;
    actionPoints: number;
}

interface TrickMovedArgs {
    player_id: number;
    trick: Trick;
    previousTrick: Trick;
}

interface TrickMarkerAddedToPerformanceArgs {
    player_id: number;
    trickMarker: TrickMarker;
    performance: Performance;
    trick: Trick;
    slotId: string;
    direction: LinkDirection;
}

interface TrickMarkerMovedToPerformanceArgs {
    player_id: number;
    trickMarker: TrickMarker;
    performance: Performance;
    trick: Trick;
    slotId: string;
    direction: LinkDirection;
}

interface AdvertisedArgs {
    playerId: number;
    cost: number;
    poster: Poster;
    fame: number;
}

interface MagicianChosenArgs {
    playerId: number;
    magician: Magician;
}

interface CharacterEnhancedArgs {
    player_id: number;
}

interface ComponentDiscardedArgs {
    player_id: number;
    component: Component;
    componentName: string;
}

interface PropheciesUpdatedArgs {
    player_id: number;
    updatedProphecies: Prophecy[];
}

interface PerformanceChosenArgs {
    player_id: number;
    performance: Performance;
}

interface CharacterIdledArgs {
    player_id: number;
    character: Character;
    assignment: Assignment;
}

interface PendingPropheciesArgs {
    prophecies: Prophecy[];
}

interface AssignmentsRevealedArgs {
    assignments: Assignment[];
}
