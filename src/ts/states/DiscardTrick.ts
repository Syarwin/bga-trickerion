import { State } from '../framework/State';
import { formatIcon } from '../format';
import { staticData } from '../staticData';

export class DiscardTrick extends State {
    onEnteringState(args: DiscardTrickArgs, isCurrentPlayerActive: boolean) {
        if (!isCurrentPlayerActive) return;

        this.bga.statusBar.removeActionButtons();

        for (const trick of args.availableTricks) {
            // Get category from static data if not on the model
            const cat = trick.category ?? (staticData.tricks[trick.type] as any)?.category;
            const icon = cat ? formatIcon(cat) : '';
            const name = (staticData.tricks[trick.type] as any)?.name ?? trick.type;
            const label = `${icon} ${_(name)}`;

            this.bga.statusBar.addActionButton(
                label,
                () => {
                    this.bga.statusBar.removeActionButtons();
                    this.bga.actions.performAction('actDiscardTrick', { trickId: trick.id });
                }
            );
        }
    }
}
