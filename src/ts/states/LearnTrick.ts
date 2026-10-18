import { State } from '../framework/State';
import { clearPossible } from '../framework/utils';
import { onClick } from '../framework/event';
import { staticData } from '../staticData';
import { cards } from '../Cards';

export class LearnTrick extends State {
  onEnteringState(args: LearnTrickArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    const selectableTrickIds = new Set(args.availableTricks.map((t) => t.id));

    $('tricks-decks')
      .querySelectorAll('.trick-card')
      .forEach((el) => {
        const trickId = parseInt((el as HTMLElement).dataset.id);
        if (selectableTrickIds.has(trickId)) {
          this.actionOnClick(el, 'actLearnTrick', { trickId });
        } else {
          el.classList.add('unselectable');
        }
      });

    // Highlight the trick deck buttons on the board
    let decks = [];
    for (const trick of args.availableTricks) {
      const card = Object.assign(trick, staticData.tricks[trick.type]);
      const deckKey = card.category;
      if (!decks.includes(deckKey)) {
        decks.push(deckKey);
        $(`trick-deck-${deckKey}`).classList.add('highlighted');
      }
    }

    // Fallback: action buttons
    this.bga.statusBar.addActionButton(_('Open tricks decks'), () => {
      const firstAvail = args.availableTricks[0];
      if (firstAvail?.category) {
        $('tricks-decks').dataset.visible = firstAvail.category;
        cards._deckModal.show();
      }
    });
  }
}
