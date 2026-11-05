import { State } from '../framework/State';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';
import { staticData } from '../staticData';
import { addCustomTooltip } from '../framework/utils';
import { meeples } from '../Meeples';

/**
 * SetupTrick - 3-phase flow:
 *   1. Select trick to place (server state)
 *   2. Select slot on any valid performance (client state: ClientSetupTrickSelectSlot)
 *   3. Select direction (client state: ClientSetupTrickSelectDirection)
 */

// ========== Server State: Phase 1 - Select Trick ==========

export class SetupTrick extends State {
  onEnteringState(args: SetupTrickArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    // Collect all unique tricks from all performances
    const trickSet = new Set<Trick>();
    args.availablePerformances.forEach((perf) => {
      const perfData = args.possibleTricksAndSlots[perf.id];
      perfData.possibleTricks.forEach((trick) => {
        trickSet.add(trick);
      });
    });

    // Click on trick cards
    Array.from(trickSet).forEach((trick) => {
      const catIcon = formatIcon(staticData.tricks[trick.type]?.category);

      const callback = () => {
        this.bga.states.setClientState('client_setupTrickSelectSlot', {
          descriptionmyturn: _('Select the performance slot where you want to place the trick marker upon (${icon})'),
          args: {
            possibleTricksAndSlots: args.possibleTricksAndSlots,
            availablePerformances: args.availablePerformances,
            selectedTrick: trick,
            icon: catIcon,
          },
        });
      };

      const oCard = $(`trick-${trick.id}`);
      onClick(oCard, callback);

      const name = staticData.tricks[trick.type]?.name;
      const btn = this.bga.statusBar.addActionButton(catIcon + _(name), callback);
      addCustomTooltip(btn, oCard.outerHTML);
    });
  }
}

// ========== Client State: Phase 2 - Select Slot ==========

interface ClientSetupTrickSelectSlotArgs extends SetupTrickArgs {
  selectedTrick: Trick;
}

export class ClientSetupTrickSelectSlot extends State {
  onEnteringState(args: ClientSetupTrickSelectSlotArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;
    this.addCancelStateBtn();
    $(`trick-${args.selectedTrick.id}`).classList.add('selected');

    // Find all performances where this trick can be placed
    args.availablePerformances.forEach((perf) => {
      const perfData = args.possibleTricksAndSlots[perf.id];
      if (!perfData?.possibleTricks?.some((t) => t.id === args.selectedTrick.id)) return;

      const perfEl = $(`performance-${perf.id}`);
      Object.entries(perfData.possibleSlots).forEach(([slotId, slot]) => {
        const slotEl = perfEl?.querySelector(`.trick-marker-slot[data-slotid="${slotId}"]`) as HTMLElement;
        onClick(slotEl, () => {
          this.bga.states.setClientState('client_setupTrickSelectDirection', {
            descriptionmyturn: _('Please choose the orientation of the trick marker ${icon} icon'),
            args: {
              ...args,
              selectedPerformanceId: perf.id,
              selectedSlotId: slotId,
              slotLinks: slot.links,
            },
          });
        });
      });
    });
  }
}

// ========== Client State: Phase 3 - Select Direction ==========

interface ClientSetupTrickSelectDirectionArgs extends ClientSetupTrickSelectSlotArgs {
  selectedPerformanceId: string;
  selectedSlotId: string;
  slotLinks: Link[];
}

export class ClientSetupTrickSelectDirection extends State {
  private _previewMeeple: HTMLElement | null = null;
  private _selectedLink: Link | null = null;

  onEnteringState(args: ClientSetupTrickSelectDirectionArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    this.addCancelStateBtn();

    const perfEl = $(`performance-${args.selectedPerformanceId}`);
    const slotEl = perfEl?.querySelector(`.trick-marker-slot[data-slotid="${args.selectedSlotId}"]`) as HTMLElement;

    // Add direction buttons
    const dirIcons: Record<string, string> = { up: '↑', down: '↓', left: '←', right: '→' };
    args.slotLinks.forEach((link) => {
      const dirIcon = dirIcons[link.direction] ?? '?';
      const shardLabel = link.shard ? ` (${formatIcon('shard')})` : '';
      this.bga.statusBar.addActionButton(
        `${dirIcon} ${shardLabel}`,
        () => {
          this._selectedLink = link;
          this._updatePreviewRotation(slotEl, args.selectedTrick, link.direction);
        },
        { id: `btnDir${link.direction}` }
      );
    });

    this.bga.statusBar.addActionButton(_('Confirm'), () => {
      this.bga.actions.performAction('actSetupTrick', {
        trickId: args.selectedTrick.id,
        performanceId: args.selectedPerformanceId,
        slotId: args.selectedSlotId,
        direction: this._selectedLink.direction,
      });
    });

    // Create preview at the slot
    this._selectedLink = args.slotLinks[0] ?? null;
    this._createPreview(slotEl, args.selectedTrick, this._selectedLink.direction);
  }

  onLeavingState() {
    this._removePreview();
  }

  private _createPreview(slotEl: HTMLElement, trick: Trick, direction: string) {
    this._removePreview();

    const trickCard = $(`trick-${trick.id}`);
    const sourceMeepleEl = trickCard?.querySelector('.meeple-trick-marker');
    this._previewMeeple = sourceMeepleEl.cloneNode(true) as any;
    this._previewMeeple.classList.add('preview');
    slotEl.insertAdjacentElement('beforeend', this._previewMeeple);
    this._updatePreviewRotation(slotEl, trick, direction);
  }

  private _removePreview() {
    if (this._previewMeeple) {
      this._previewMeeple.remove();
      this._previewMeeple = null;
    }
  }

  private _updatePreviewRotation(_slotEl: HTMLElement, trick: Trick, direction: string) {
    if (!this._previewMeeple) return;

    const el = this._previewMeeple;
    const rotation = this._computeRotation(trick, direction);
    el.style.rotate = `${rotation}deg`;

    $('generalactions')
      .querySelectorAll('.selected')
      .forEach((o) => o.classList.remove('selected'));
    $(`btnDir${direction}`).classList.add('selected');
  }

  private _computeRotation(trick: Trick, direction: string): number {
    const categoryToCorner: Record<string, number> = {
      spiritual: 0,
      mechanical: 1,
      optical: 2,
      escape: 3,
    };

    const directionToTarget: Record<string, number> = {
      up: 0,
      right: 1,
      down: 2,
      left: 3,
    };

    const cornerIndex = categoryToCorner[trick.category ?? ''] ?? 0;
    const targetIndex = directionToTarget[direction] ?? 0;

    const baseRotation = -90;
    const additionalRotation = (targetIndex - cornerIndex) * 90;

    return baseRotation + additionalRotation;
  }
}
