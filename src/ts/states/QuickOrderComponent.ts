import { State } from '../framework/State';
import { addCustomTooltip, clearPossible } from '../framework/utils';
import { formatIcon, getComponentDisplayName } from '../format';

export class QuickOrderComponent extends State {
  onEnteringState(args: QuickOrderComponentArgs, isCurrentPlayerActive: boolean) {
    if (!isCurrentPlayerActive) return;

    args.availableComponents.forEach((componentType) => {
      const comp = getComponentDisplayName(componentType);
      const label = _(comp);
      //   const label = `${formatIcon('component-' + componentType)}`;
      this.addActionBtn(label, 'actQuickOrderComponents', { componentType }, { color: 'secondary', id: `btn${componentType}` });
      //   addCustomTooltip($(`btn${componentType}`), comp);
    });
  }
}
