/**
 * Notifications Utilities
 * 
 * Provides uniform formatting for notifications involving tricks, components, characters, etc.
 * Handles i18n translation, icon display, and proper formatting.
 */

import { formatIcon } from '../format';

// Map of playerId to color for suit marker coloring
const playerColors: Record<number, string> = {};

/**
 * Initialize player colors from gamedatas
 * @param gamedatas - The game data containing player information
 */
export function initPlayerColors(gamedatas: any): void {
    if (gamedatas?.players) {
        for (const playerId in gamedatas.players) {
            const player = gamedatas.players[playerId];
            if (player?.color) {
                playerColors[parseInt(playerId)] = player.color;
            }
        }
    }
}

/**
 * Get the color for a player
 * @param playerId - The player ID
 * @returns The player's color hex code
 */
export function getPlayerColor(playerId: number): string {
    return playerColors[playerId] || '#666666'; // Default gray if not found
}

/**
 * Format a trick for display in notifications
 * @param trick - The trick object from notification args
 * @returns Formatted HTML string with trick name, category icon, and suit icon
 */
export function formatTrick(trick: any): string {
    if (!trick) return 'unknown';
    
    // Get the trick type and name
    const type = trick.type || 'unknown';
    const name = trick.name || '';
    const suit = trick.suit || '';
    const category = trick.category || '';
    
    // Build the formatted string with icons
    let result = '';
    
    // Add category icon if available
    if (category) {
        result += `<i class='svgicon-${category.toLowerCase()}'></i> `;
    }
    
    // Add the trick name/translation
    if (name) {
        // If we have a name, use it (BGA will handle translation if it's a translation key)
        result += (typeof _ !== 'undefined' ? _(name) : name);
    } else {
        // Use type which BGA will translate
        result += type;
    }
    
    // Add suit icon if available
    if (suit) {
        result += ` <i class='svgicon-${suit.toLowerCase()}'></i>`;
    }
    
    return result;
}

/**
 * Format a trick marker for display in notifications
 * @param marker - The trick marker object from notification args
 * @returns Formatted HTML string with colored suit icon
 */
export function formatTrickMarker(marker: any): string {
    if (!marker) return 'Trick marker';
    
    const suit = marker.suit || '';
    const playerId = marker.playerId;
    
    // If we have suit, show the suit icon with player color
    if (suit) {
        const color = playerId ? getPlayerColor(playerId) : '#666666';
        return `<i class='svgicon-${suit.toLowerCase()}' style='color: ${color}'></i>`;
    }
    
    return 'Trick marker';
}

/**
 * Format multiple trick markers for display
 * @param markers - Array of trick marker objects
 * @param count - Optional count override
 * @returns Formatted string describing the markers
 */
export function formatTrickMarkers(markers: any[], count?: number): string {
    if (!markers || markers.length === 0) return '';
    
    const markerCount = count ?? markers.length;
    
    if (markerCount === 1 && markers[0]) {
        return formatTrickMarker(markers[0]);
    } else {
        return `${markerCount} trick markers`;
    }
}

/**
 * Format a component for display in notifications
 * @param component - The component object from notification args
 * @returns Formatted HTML string with component icon and name
 */
export function formatComponent(component: any): string {
    if (!component) return 'Component';
    
    const type = component.type || component || 'unknown';
    const icon = formatIcon(type, null, false);
    const name = typeof _ !== 'undefined' ? _(type) : type;
    
    return icon + ' ' + name;
}

/**
 * Format a character for display in notifications
 * @param character - The character object from notification args
 * @returns Formatted HTML string with character icon and name
 */
export function formatCharacter(character: any): string {
    if (!character) return 'Character';
    
    const type = character.type || character || 'unknown';
    const icon = formatIcon(type, null, false);
    const name = typeof _ !== 'undefined' ? _(type) : type;
    
    return icon + ' ' + name;
}

/**
 * Create a trick notification decorator that provides uniform formatting
 * This can be used in logOverride or in custom notification handlers
 */
export const trickFormatter = {
    format: formatTrick,
    formatMarker: formatTrickMarker,
    formatMarkers: formatTrickMarkers,
    
    /**
     * Format for logOverride - returns a string that replaces ${trick} in messages
     */
    forLog: (args: { trick: any }) => formatTrick(args.trick),
    
    /**
     * Format for previousTrick in logOverride
     */
    forPreviousTrickLog: (args: { previousTrick?: any }) => {
        const trick = args.previousTrick;
        return trick ? formatTrick(trick) : 'unknown';
    }
};
