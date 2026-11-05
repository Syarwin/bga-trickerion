import { State } from '../framework/State';
import { players } from '../Players';

/**
 * EnhanceCharacter visual state.
 *
 * Automatic action triggered by certain Magician abilities.
 * The `notif_characterEnhanced` notification handles any visual feedback.
 * Currently this is a backend-driven auto state with no UI interaction needed.
 */
export class EnhanceCharacter extends State {
  onEnteringState(_args: object, _isCurrentPlayerActive: boolean) {
    // Auto state — no user interaction needed
  }

  onLeavingState(_args: object, _isCurrentPlayerActive: boolean) {
    // Nothing to clean up
  }

  onPlayerActivationChange(_args: object, _isCurrentPlayerActive: boolean) {
    // Auto state — not player-interactive
  }

  async notif_characterEnhanced(_args: CharacterEnhancedArgs) {
    await players.animatePlayerCounter(_args.player_id, 'shard', -1);
  }
}
