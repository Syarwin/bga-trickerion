import { Game } from '../Game';
import { clearPossible, attachRegisteredTooltips } from '../framework/utils';
import { onClick } from '../framework/event';
import { irreversibleAction } from '../framework/engine';
import { cards } from '../Cards';

/**
 * DrawAssignmentCards visual state.
 *
 * Two phases:
 *   1. Draw phase — clickable deck spaces on the Dark Alley board (or buttons fallback).
 *   2. Discard phase — drawn cards shown as actual assignment card templates in an
 *      overlay container with click-to-toggle selection, then a Discard button.
 */
export class DrawAssignmentCards {
    game: Game;
    bga: ExtendedBga;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: DrawAssignmentCardsArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: DrawAssignmentCardsArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: DrawAssignmentCardsArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        // Phase 1: Draw — highlight clickable deck spaces on the Dark Alley board
        let highlighted = false;
        for (const locationId of args.availableLocations) {
            const deckEl = $(`assignment-deck-${this._deckShortName(locationId)}`);
            if (deckEl) {
                deckEl.classList.add('selectable');
                highlighted = true;

                onClick(deckEl, irreversibleAction(() => {
                    clearPossible();
                    this._cleanup();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actDrawAssignmentCards', { deckLocationId: locationId });
                }));
            }
        }

        // Fallback: deck buttons if no DOM elements found
        if (!highlighted) {
            for (const locationId of args.availableLocations) {
                const label = `${this._deckLabel(locationId)} (-${args.currentDrawCost} AP)`;
                const disabled = !args.canDraw;
                this.bga.statusBar.addActionButton(
                    _(label),
                    irreversibleAction(() =>
                        this.bga.actions.performAction('actDrawAssignmentCards', { deckLocationId: locationId })
                    ),
                    { disabled }
                );
            }
        }

        // Phase 2: Discard — show drawn cards as selectable assignment cards
        if (args.drawnCards.length > 0) {
            this._showDrawnCards(args);
        }
    }

    private _showDrawnCards(args: DrawAssignmentCardsArgs) {
        // Create a container for drawn cards if not already present
        let drawnContainer = $('#drawn-cards-container');
        if (!drawnContainer) {
            drawnContainer = document.createElement('div');
            drawnContainer.id = 'drawn-cards-container';
            drawnContainer.className = 'drawn-cards-container';
            const gameArea = $('#game_play_area');
            if (gameArea) gameArea.appendChild(drawnContainer);
        }
        drawnContainer.innerHTML = '';

        // Title
        drawnContainer.insertAdjacentHTML('beforeend', 
            `<div class="drawn-cards-title">${_('Choose cards to discard — kept cards return to hand')}</div>`);

        for (const card of args.drawnCards) {
            const html = cards.tplAssignmentCard(card);
            drawnContainer.insertAdjacentHTML('beforeend', html);
        }
        attachRegisteredTooltips();

        // Make cards toggleable with visual selection
        const cardElements = drawnContainer.querySelectorAll('.assignment-card');
        cardElements.forEach((el) => {
            el.classList.add('selectable-toggle');
            el.addEventListener('click', () => {
                el.classList.toggle('selected');
            });
        });

        // Discard button
        this.bga.statusBar.addActionButton(
            _('Discard selected / keep rest'),
            () => {
                const selectedIds: string[] = [];
                drawnContainer.querySelectorAll('.assignment-card.selected').forEach((el) => {
                    const id = el.id?.replace('assignment-card-', '');
                    if (id) selectedIds.push(id);
                });
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                this.bga.actions.performAction('actDiscardCards', { cardIds: selectedIds });
            }
        );
    }

    private _cleanup() {
        const container = $('#drawn-cards-container');
        if (container) container.remove();

        // Remove selectable from deck elements
        document.querySelectorAll('[id^="assignment-deck-"]').forEach((el) => {
            el.classList.remove('selectable');
        });
    }

    private _deckLabel(locationId: string): string {
        const map: Record<string, string> = {
            theater_deck: 'Theater deck',
            workshop_deck: 'Workshop deck',
            market_row_deck: 'Market Row deck',
            downtown_deck: 'Downtown deck',
        };
        return map[locationId] ?? locationId;
    }

    private _deckShortName(locationId: string): string {
        // assignment-deck-{short} maps to #assignment-deck-{short} in the Dark Alley DOM
        return locationId.replace('_deck', '');
    }
}
