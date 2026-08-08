import { Game } from '../Game';
import { clearPossible, getCurrentPlayerId } from '../framework/utils';
import { onClick } from '../framework/event';
import { formatIcon } from '../format';

export class MoveComponents {
    game: Game;
    bga: ExtendedBga;
    private _currentArgs: MoveComponentsArgs | null = null;

    constructor(game: Game, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
    }

    onEnteringState(args: MoveComponentsArgs, isCurrentPlayerActive: boolean) {
        this._currentArgs = args;
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: MoveComponentsArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
    }

    onPlayerActivationChange(args: MoveComponentsArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        this._cleanup();
        if (!isCurrentPlayerActive) return;

        this._currentArgs = args;
        this.bga.statusBar.removeActionButtons();

        // Phase 1: Highlight available component meeples on player board
        this._showPlayerComponents(args);
    }

    /** Phase 1: Highlight component meeples on the player board that can be moved */
    private _showPlayerComponents(args: MoveComponentsArgs) {
        const playerId = getCurrentPlayerId();

        // Try highlighting the actual component meeple elements in the DOM
        let highlighted = false;
        for (const component of args.availableComponents) {
            const el = $(`meeple-${component.type}-${component.id}`);
            if (!el) continue;

            el.classList.add('selectable');
            highlighted = true;

            onClick(el, () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();

                if (args.usedSlots.length < 2) {
                    // Direct move — manager board has free slot
                    this.bga.actions.performAction('actMoveComponent', {
                        componentId: component.id,
                        toReplaceComponentId: null,
                    });
                } else {
                    // Need to choose which component to replace
                    this._showReplaceSelection(args, component);
                }
            });
        }

        // Also highlight the component slot containers as visual cue
        const boardEl = $(`magician-board-${playerId}`);
        if (boardEl) {
            boardEl.querySelectorAll('.magician-tricks-components .component-slot').forEach((slot) => {
                const meeple = slot.querySelector('.trickerion-meeple');
                if (meeple && meeple.classList.contains('selectable')) {
                    slot.classList.add('target-highlight');
                }
            });
        }

        // Fallback: action buttons if no meeples found in DOM
        if (!highlighted) {
            for (const component of args.availableComponents) {
                const label = `${formatIcon(component.type)} ${_(component.type)}${component.count > 1 ? ` ×${component.count}` : ''}`;
                this.bga.statusBar.addActionButton(
                    label,
                    () => {
                        clearPossible();
                        this.bga.statusBar.removeActionButtons();
                        if (args.usedSlots.length < 2) {
                            this.bga.actions.performAction('actMoveComponent', {
                                componentId: component.id,
                                toReplaceComponentId: null,
                            });
                        } else {
                            this._showReplaceSelection(args, component);
                        }
                    }
                );
            }
        }
    }

    /** Phase 2: Choose which component on manager board to replace + Cancel */
    private _showReplaceSelection(args: MoveComponentsArgs, component: Component) {
        // Highlight the used slot meeples on the manager board
        let highlighted = false;
        for (const used of args.usedSlots) {
            const el = $(`meeple-${used.type}-${used.id}`);
            if (el) {
                el.classList.add('selectable');
                highlighted = true;
            }
        }

        // Highlight manager component slot containers
        const playerId = getCurrentPlayerId();
        const boardEl = $(`magician-board-${playerId}`);
        if (boardEl) {
            boardEl.querySelectorAll('.manager-workshop .component-slot').forEach((slot) => {
                const meeple = slot.querySelector('.trickerion-meeple');
                if (meeple && meeple.classList.contains('selectable')) {
                    slot.classList.add('target-highlight');
                }
            });
        }

        // Attach click handlers for replace targets (if DOM elements found)
        if (highlighted) {
            for (const used of args.usedSlots) {
                const el = $(`meeple-${used.type}-${used.id}`);
                if (!el) continue;

                onClick(el, () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actMoveComponent', {
                        componentId: component.id,
                        toReplaceComponentId: used.id,
                    });
                });
            }
        }

        // Always show buttons for replace targets + Cancel
        this.bga.statusBar.removeActionButtons();
        for (const used of args.usedSlots) {
            const label = _('Replace') + ` ${formatIcon(used.type)} ${_(used.type)}`;
            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actMoveComponent', {
                        componentId: component.id,
                        toReplaceComponentId: used.id,
                    });
                }
            );
        }

        this.bga.statusBar.addActionButton(
            _('Cancel'),
            () => {
                clearPossible();
                this.bga.statusBar.removeActionButtons();
                if (this._currentArgs) this._showPlayerComponents(this._currentArgs);
            },
            { color: 'alert' }
        );
    }

    private _cleanup() {
        document.querySelectorAll('.trickerion-meeple[id^="meeple-"]').forEach((el) => {
            el.classList.remove('selectable');
        });
        document.querySelectorAll('.component-slot').forEach((el) => {
            el.classList.remove('target-highlight', 'selectable');
        });
    }
}
