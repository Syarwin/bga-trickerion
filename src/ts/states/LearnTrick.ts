import { Game } from '../Game';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';

/**
 * LearnTrick visual state.
 *
 * Reuses the existing trick-deck modal (created in Cards.setupTrickModal()).
 * When the state is active, the trick deck buttons on the board are highlighted
 * and clicking them opens the modal where available tricks are clickable.
 */
export class LearnTrick {
    game: Game;
    bga: ExtendedBga;
    private _selectableTrickIds: Set<number> = new Set();

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: LearnTrickArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: LearnTrickArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: LearnTrickArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();

        if (!isCurrentPlayerActive) return;

        this._selectableTrickIds = new Set(args.availableTricks.map((t) => t.id));

        // Highlight the trick deck buttons on the board
        for (const trick of args.availableTricks) {
            const deckKey = trick.category;
            if (!deckKey) continue;
            const deckBtn = $(`trick-deck-${deckKey}`);
            if (deckBtn) {
                deckBtn.style.boxShadow = '0 0 12px 4px gold';
                deckBtn.style.cursor = 'pointer';

                onClick(deckBtn, () => {
                    // Switch the modal to the right tab
                    const decks = $('tricks-decks');
                    if (decks) decks.dataset.visible = deckKey;
                    // The modal is opened by the existing click handler, but we
                    // need our own because the observer-based handler is already attached.
                    // We'll open it and then mark tricks selectable after DOM updates.
                    // Instead, just navigate to the tab and rely on the existing click.
                    // We mark selectable after a short delay to let the modal render.
                    this._markAvailableTricksInModal();
                });
            }
        }

        // Also mark tricks available in the modal if it's already open
        this._markAvailableTricksInModal();

        // Fallback: action buttons
        this.bga.statusBar.addActionButton(
            _('Open trick deck'),
            () => {
                // Navigate to first available trick's tab
                const firstAvail = args.availableTricks[0];
                if (firstAvail?.category) {
                    const decks = $('tricks-decks');
                    if (decks) decks.dataset.visible = firstAvail.category;
                    // Trigger the deck button click to open the modal
                    const deckBtn = $(`trick-deck-${firstAvail.category}`);
                    if (deckBtn) deckBtn.click();
                }
                this._markAvailableTricksInModal();
            }
        );
    }

    /**
     * Add selectable/unselectable classes to trick cards in the modal
     * and attach click handlers for available ones.
     */
    private _markAvailableTricksInModal() {
        // Wait a frame for the modal DOM to settle
        setTimeout(() => {
            const allFromArgs = this.game.gamedatas.tricks.available;
            for (const trick of allFromArgs) {
                const el = $(`trick-${trick.id}`);
                if (!el) continue;

                if (this._selectableTrickIds.has(trick.id)) {
                    el.classList.add('selectable');
                    el.classList.remove('unselectable');
                    onClick(el, () => {
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        this.bga.actions.performAction('actLearnTrick', { trickId: trick.id });
                    });
                } else {
                    el.classList.add('unselectable');
                    el.classList.remove('selectable');
                }
            }
        }, 100);
    }

    private _cleanup() {
        this._selectableTrickIds = new Set();
        // Remove highlight from deck buttons
        document.querySelectorAll('[id^="trick-deck-"]').forEach((btn) => {
            (btn as HTMLElement).style.boxShadow = '';
            (btn as HTMLElement).style.cursor = '';
        });
    }
}
