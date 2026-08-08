# Trickerion Frontend TODO

## Legend

-   🟢 Done - proper visual/interactive UI
-   🟡 Partial - basic buttons, needs visual/interactive upgrade
-   🔴 Empty/stub - no UI created at all
-   📢 Empty notification handler

---

## 1. Game States — UI/UX Implementation

### 🟢 All game states implemented

All state files in `src/ts/states/` have at least a working UI. No 🔴 stubs remain.

| #   | State file                 | Status | Details                                                                 |
| --- | -------------------------- | ------ | ----------------------------------------------------------------------- |
| 1   | **ChooseMagician.ts**      | 🟢     | Card templates, tooltips, onSelectN                                     |
| 2   | **AssignCharacters.ts**    | 🟢     | Card+slot DOM, click-to-assign, slide animations                        |
| 3   | **PlaceCharacter.ts**      | 🟢     | Clickable slots, board highlighting                                     |
| 4   | **PlayLocationAction.ts**  | 🟢     | Clickable action spaces on board                                        |
| 5   | **Performance.ts**         | 🟢     | Clickable performance cards, chosen/dimmed CSS                          |
| 6   | **SetupTrick.ts**          | 🟢     | 4-phase wizard (perf→trick→slot→direction)                              |
| 7   | **Reschedule.ts**          | 🟢     | 4-phase wizard (marker→perf→slot→direction)                             |
| 8   | **Advertise.ts**           | 🟢     | Cost display, Skip, theater action highlight, poster render             |
| 9   | **BuyComponents.ts**       | 🟢     | Clickable buy slots, count selector, bargain                            |
| 10  | **OrderComponent.ts**      | 🟢     | Component icons, clickable order slots                                  |
| 11  | **QuickOrderComponent.ts** | 🟢     | Component icons, single-click place                                     |
| 12  | **HireCharacter.ts**       | 🟢     | Clickable dice, slide animation                                         |
| 13  | **LearnTrick.ts**          | 🟢     | Trick deck modal, deck buttons, clickable tricks                        |
| 14  | **TakeCoins.ts**           | 🟢     | Clickable bank dice, animated roll                                      |
| 15  | **PrepareTrick.ts**        | 🟢     | Clickable trick cards, action highlight                                 |
| 16  | **MoveTrick.ts**           | 🟢     | Clickable tricks + engineer slot                                        |
| 17  | **MoveComponents.ts**      | 🟢     | Clickable component meeples                                             |
| 18  | **MoveApprentice.ts**      | 🟢     | Clickable idle slots                                                    |
| 19  | **DiscardComponents.ts**   | 🟢     | Component icons, count, animated removal                                |
| 20  | **DiscardTrick.ts**        | 🟢     | Trick buttons with icons                                                |
| 21  | **RerollDie.ts**           | 🟢     | Clickable dice, confirmation dialog, animated roll                      |
| 22  | **SetDie.ts**              | 🟢     | 2-phase (die→face), cancel                                              |
| 23  | **MakeDieUnavailable.ts**  | 🟢     | Clickable dice on downtown board                                        |
| 24  | **DrawAssignmentCards.ts** | 🟢     | Clickable Dark Alley deck spaces, drawn card overlay with toggle select |
| 25  | **PickComponents.ts**      | 🟢     | Component icons with budget display, affordability check                |
| 26  | **FortuneTelling.ts**      | 🟢     | Auto state with `notif_propheciesUpdated` (rotation animation)          |
| 27  | **EnhanceCharacter.ts**    | 🟢     | Auto state with handler stub                                            |
| 28  | **FinishSetup.ts**         | 🟢     | Renders initial prophecies                                              |
| 29  | **StartAssignment.ts**     | 🟢     | Shows other players' assignments organized by player                    |
| 30  | **PlaceCharacters.ts**     | 🟢     | `notif_assignmentsRevealed` CSS flip animation                          |

### Framework states (all functional)

-   **ConfirmTurn.ts**, **ConfirmPartialTurn.ts**, **ResolveChoice.ts**, **DummyEnd.ts**, **AnytimeActions.ts**

---

## 2. Notifications — Implementation Status

### ✅ All 16 notification groups implemented

| #   | File                            | Status | Notes                                                                   |
| --- | ------------------------------- | ------ | ----------------------------------------------------------------------- |
| N1  | **AssignmentNotifications.ts**  | 🟢     | assign, unassign, return, discard, reset                                |
| N2  | **CharacterNotifications.ts**   | 🟢     | hired, returned, wages, placed, apprentice moved                        |
| N3  | **ComponentNotifications.ts**   | 🟢     | bought, moved, discarded (with animations)                              |
| N4  | **DieNotifications.ts**         | 🟢     | rolled, made-unavailable, rerolled, set                                 |
| N5  | **MarketRowNotifications.ts**   | 🟢     | buy-area, quick-order, order, arrive, clear                             |
| N6  | **PerformanceNotifications.ts** | 🟢     | lifecycle, links, yields, trick markers                                 |
| N7  | **PosterNotifications.ts**      | 🟢     | posters returned (remove from board)                                    |
| N8  | **ProphecyNotifications.ts**    | 🟢     | active/pending lifecycle                                                |
| N9  | **TrickNotifications.ts**       | 🟢     | learned, discarded, prepared, moved                                     |
| N10 | **TrickMarkerNotifications.ts** | 🟢     | added/moved to performance slots (correct grid position), rich tooltips |
| N11 | **PlayerNotifications.ts**      | 🟢     | coins, shards, fame (animated counters), initiative                     |
| N12 | **Performance.ts** (state)      | 🟢     | chosen performance → dim others, highlight chosen                       |
| N13 | **EnhanceCharacter.ts**         | 🟢     | notification handler stub                                               |
| N14 | **FortuneTelling.ts**           | 🟢     | propheciesUpdated with rotation animation                               |
| N15 | **FinishSetup.ts**              | 🟢     | pendingProphecies renders on Dark Alley                                 |
| N16 | **Advertise.ts** (state)        | 🟢     | poster render with fade-in                                              |

---

## 3. Other Frontend Work — Status Summary

| #   | Task                  | Status | Notes                                                                                                                                                        |
| --- | --------------------- | ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 3a  | Performance board     | 🟢     | Cards render with grid slots, trick markers placed via notifications                                                                                         |
| 3b  | Trick marker grid     | 🟢     | Notification places markers on correct `.trick-marker-slot[data-slotid]` with counter-rotation CSS for upright display; fallback to pending if no slot found |
| 3c  | Academy markers       | ⏳     | Not yet in backend                                                                                                                                           |
| 3d  | Enhance character     | 🟢     | Handler stub ready for when backend sends the notification                                                                                                   |
| 3e  | Fame counter          | 🟢     | `ebg.counter` in player panel with slide animation                                                                                                           |
| 3f  | Component display     | 🟢     | Meeple count badge, removed when count=0                                                                                                                     |
| 3g  | Hired specialists     | 🟢     | `notif_characterHired` updates `updateHiredSpecialists()`                                                                                                    |
| 3h  | Market row display    | 🟢     | Icons rendered and updated via notifications                                                                                                                 |
| 3i  | Assignment animations | 🟢     | Draw: overlay with cards; Pending: slide animation; Discard: cleanup on leave                                                                                |
| 3j  | Prophecy display      | 🟢     | Active & pending containers in Dark Alley, all notifications implemented                                                                                     |
| 3k  | Posters on board      | 🟢     | Rendered on board, returned notifications clear them                                                                                                         |
| 3l  | "Perform" action      | 🟢     | Handled by `Performance.ts` state → `actSelectPerformance` → backend `$performance->perform()`                                                               |
| 3m  | Trick card suit/color | 🟢     | `data-suit`/`data-color` attributes set, CSS renders correct symbol marker                                                                                   |

---

## 4. Current Feedback Items from Testing

### ✅ Resolved
- **Trick Markers on Trick Cards**: Fixed display of trick markers on prepared tricks (Mechaniker, Great Optico, Priestess of Mysticism)
- **Advertising Poster Overlay**: Added poster icon to magician card and player panel summary
- **Assistant Assignment Highlight**: Fixed highlight for apprentice on assistant board

### 🔄 In Progress
- **Component Display Issue**: Mechaniker shows Fabric instead of Metal, Great Optico shows Metal instead of Fabric
- **Browser Refresh Issue**: Setup steps require browser refresh to display choices made by each player

## 5. Priority for Future Work

Most frontend work is complete. Future priorities would be:

1. **Trick marker rendering on init** — During `meeples.init()`, trick markers with `available`/`prepared`/`scheduled` locations go to `#trickerion-pending`. Once the backend sets their location to performance-card-specific locations, `getMeepleContainer` needs to map those to the correct `.trick-marker-slot` elements.

2. **Performance card slot link visualization** — Show link direction arrows and shard bonuses on the performance card slot grid (CSS overlay on the `.trick-marker-slot` elements).

3. **Deanimation/UX polish** — Fade-in/fade-out transitions for panels, smoother card animations, responsive layout refinements.

4. **Backend-driven features** — Academy markers, remaining Magician abilities, any new game mechanics.

---

## 5. Key Knowledge for Implementation

### Architecture

-   State files in `src/ts/states/`, registered in `Game.ts` via `bga.states.register('Name', new NameClass(this, bga))`
-   Notifications auto-wired: method `notif_xxx` handles notification `xxx` from PHP
-   All players in `notifications/index.ts` array; individual states can also have `notif_xxx` methods
-   State args typed in `src/ts/types.d.ts` as `XxxArgs` interfaces
-   Notification args typed in `src/ts/types.d.ts` as `XxxArgs` interfaces

### UI Patterns

-   Use `cards.tplXxx()` for card templates (performance, assignment, trick, magician, prophecy, poster)
-   Use `meeples.addMeeple()` / `meeples.tplMeeple()` for character/component/trick-marker pieces
-   Use `bga.statusBar.addActionButton(label, callback, options?)` for action buttons
-   Use `onClick(element, callback)` from `framework/event.ts` for clickable DOM elements
-   Use `onSelectN({ elements, n, callback })` from `framework/utils.ts` for n-of-many selection
-   Use `board.getAnimationManager()` → `.slideAndAttach()` / `.slideFloatingElement()` for animations
-   Use `clearPossible()` on state leave to clean up hover/select states
-   Use `addCustomTooltip(element, html)` or `registerCustomTooltip(html, id) + attachRegisteredTooltips()` for tooltips
-   Use `formatIcon('name', count?)` for icons (coin, shard, fame, action-point, etc.)
-   Use `formatString(str)` for text with `<iconname>` replacement + `{{emphasis}}`

### Board DOM structure

-   Downtown: `#board-downtown-inner` with die slots, trick decks, action spaces, character slots
-   Market Row: `#board-market-row-inner` with buy area, order area, quick order, action spaces
-   Theater: `#board-theater-inner` with performance slots (`.performance-slot-{n}`), action slots, link bonus display
-   Dark Alley: `#board-dark-alley-inner` (only if isDarkAlley) with assignment decks, prophecy slots, action spaces
-   Dark Alley prophecies: `.slot-prophecies` container with `#alley-active-prophecy` and `#alley-pending-prophecies` children
-   Player boards: `#magician-board-{playerId}` with workshop, trick slots, component slots, assignment slots
-   Assignment slots: `#assignment-slot-{playerId}-{type}` where type is magician/apprentice-1/2/3/engineer/manager/assistant/apprentice-assistant
-   Pending area: `#trickerion-pending` — general-purpose container for temporary UI elements
-   Floating assignments: `#floating-assignments-wrapper` -> `#floating-assignments`

### Meeples

-   Characters rendered in assignment idle slots (by `CharacterLocation`)
-   Components on player board component slots or manager component slots
-   Trick markers placed on performance card `.trick-marker-slot` elements (via `TrickMarkerNotifications`)
-   `meeples.getMeepleContainer(meeple)` maps backend location → DOM element
-   `meeples.addMeeple(meeple, container?)` creates and inserts a meeple DOM element
-   Trick marker meeple tooltips show trick name, player, and suit

### Dice

-   Uses `bga-dice` library via `useDice()` from libLoader
-   6 dice slots: character-0, character-1, trick-0, trick-1, money-0, money-1
-   Each die has 6 faces defined in `FACE_DEFS`
-   `dice.rollDie(slotKey, newFace)` for animated rolls
-   Tooltip shows all 6 faces with current one highlighted

### Not yet implemented on backend (no frontend needed yet):

-   Academy markers
-   Some Magician abilities (their effects come through the engine/anytime actions)
