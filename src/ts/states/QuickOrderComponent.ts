import { State } from '../framework/State';
import { clearPossible } from '../framework/utils';
import { formatIcon } from '../format';

export class QuickOrderComponent extends State {
    onEnteringState(args: QuickOrderComponentArgs, isCurrentPlayerActive: boolean) {
        this.onPlayerActivationChange(args, isCurrentPlayerActive);
    }

    onLeavingState(_args: QuickOrderComponentArgs, _isCurrentPlayerActive: boolean) {
        clearPossible();
    }

    onPlayerActivationChange(args: QuickOrderComponentArgs, isCurrentPlayerActive: boolean) {
        clearPossible();
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        // Single-step: show available component types, click to place on quick order slot
        for (const component of args.availableComponents) {
            const label = `${formatIcon(component)} ${_(component)}`;
            this.bga.statusBar.addActionButton(
                label,
                () => {
                    clearPossible();
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actQuickOrderComponents', { component });
                }
            );
        }
    }
}
