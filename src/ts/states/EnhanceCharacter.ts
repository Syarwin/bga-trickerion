import { State } from "../framework/State";

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
        // Enhancement effect is handled through game engine/anytime actions.
        // Could add a brief visual flash on the magician card if needed.
    }
}
