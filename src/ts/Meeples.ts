/**
 * Meeples — manages all board pieces (characters, components, trick markers, academy markers).
 *
 * On init() it reads gamedatas and creates meeple DOM elements positioned in the correct slots.
 * getMeepleLocation() maps backend location strings → DOM elements, with a fallback hidden div
 * for robustness when the location has no visual slot yet.
 */

/** Map player color hex → sprite color name */
const _colorName = (hex: string): string => {
    const map: Record<string, string> = {
        '60aaa1': 'blue',
        cf4a1f: 'red',
        cc7f17: 'yellow',
        '85902b': 'green',
    };
    return map[hex.toLowerCase()] ?? hex;
};

/** Player id → color hex, populated by init() */
let _playerColors: Record<number, string> = {};

export const meeples = {
    /**
     * Initialize all meeples from gamedatas.
     * Called from board.init() after the DOM is built.
     */
    init(gamedatas: TrickerionGamedatas): void {
        _playerColors = {};
        for (const player of Object.values(gamedatas.players)) {
            _playerColors[player.id] = player.color;
        }

        // 1. Characters (visible = not in supply)
        for (const character of gamedatas.characters.visible) {
            this.createMeeple(character);
        }

        // visible already excludes supply, but if a supply meeple sneaks in, getMeepleLocation
        // will return fallbackEl and log an error.

        // 2. Components (only those with count > 0)
        for (const playerIdStr of Object.keys(gamedatas.components.player)) {
            const playerId = parseInt(playerIdStr, 10);
            const components = gamedatas.components.player[playerId];
            for (const component of components) {
                if (component.count <= 0) continue;
                this.createMeeple(component);
            }
        }

        // 3. Trick markers — available/prepared/scheduled are placed in the pending area for now
        const allTrickMarkers = [
            ...gamedatas.trickMarkers.available,
            ...gamedatas.trickMarkers.prepared,
            ...gamedatas.trickMarkers.scheduled,
        ];
        for (const tm of allTrickMarkers) {
            this.createMeeple(tm);
        }

        // 4. Academy markers — not yet supported in backend, skip for now
    },

    /**
     * Create a meeple DOM element inside the given container.
     *
     * Uses tplMeeple() to generate the HTML, then inserts it via insertAdjacentHTML.
     *
     * @param container The parent element to insert into.
     * @param meeple    The meeple data.
     * @returns         The created element (fetched from the DOM after insertion).
     */
    createMeeple(meeple: Meeple, container?: HTMLElement): HTMLElement {
        if ($(`meeple-${meeple.id}`)) return;

        if (!container) container = this.getMeepleLocation(meeple);
        container.insertAdjacentHTML('beforeend', this.tplMeeple(meeple));
        const el = container.lastElementChild as HTMLElement;
        if (el) {
            return el;
        }
    },

    /**
     * Return the HTML string for a meeple element.
     */
    tplMeeple(meeple: Meeple): string {
        let cssClass: string;
        let count: number | undefined;

        if ('suit' in meeple) {
            // TrickMarker
            const colorHex = _playerColors[meeple.playerId] ?? '';
            cssClass = `meeple-trick-marker-${_colorName(colorHex)}-${meeple.suit}  meeple-trick-marker`;
        } else if ('count' in meeple) {
            // Component
            cssClass = `meeple-component-${meeple.type}  meeple-component`;
            count = meeple.count;
        } else {
            // Character
            const colorHex = _playerColors[meeple.playerId] ?? '';
            cssClass = `meeple-${_colorName(colorHex)}-${(meeple as Character).type} meeple-character`;
        }

        const dataAttr = count !== undefined && count > 0 ? ` data-count="${count}"` : '';
        return `<div id='meeple-${meeple.id}' class="trickerion-meeple ${cssClass}"${dataAttr}></div>`;
    },

    /**
     * Map a backend location string to a DOM element.
     *
     * Handles board slots, idle slots, and player-specific workshop slots.
     * Always returns a DOM element — logs an error + returns fallback if the
     * location has no visible slot or is unknown.
     */
    getMeepleLocation(meeple: Meeple): HTMLElement {
        const fallback = $('trickerion-default-container');

        const location = meeple.location;
        const playerId = meeple.playerId;
        // Extract character type for assistant-board disambiguation
        const characterType = 'type' in meeple ? ((meeple as Character).type as string | undefined) : undefined;
        // Locations that have no visible DOM
        if (location === 'supply' || location === 'incoming') {
            console.error(`[Meeples] Meeple at "${location}" has no visible DOM element`, meeple);
            return fallback;
        }

        // Direct board slots (location equals DOM id)
        const directIdMatch = location.match(/^board-(downtown|market-row|dark-alley)-(\d+)$/);
        if (directIdMatch) {
            const el = document.getElementById(location);
            if (el) return el;
            console.error(`[Meeples] Board slot element #${location} not found in DOM`, meeple);
            return fallback;
        }

        // Basic 1, Basic 2, Magician theater slots
        const theaterMatch = location.match(/^board-theater-(thursday|friday|saturday|sunday)-(basic-1|basic-2|magician)$/);
        if (theaterMatch) {
            const el = document.getElementById(location);
            if (el) return el;
            console.error(`[Meeples] Theater slot element #${location} not found in DOM`, meeple);
            return fallback;
        }

        // Workshop slots: board-workshop-{1,2} → #board-workshop-{playerId}-{n}
        const workshopMatch = location.match(/^board-workshop-(\d+)$/);
        if (workshopMatch) {
            if (playerId === undefined) {
                console.error(`[Meeples] Workshop location ${location} requires playerId`, meeple);
                return fallback;
            }
            const slotNumber = workshopMatch[1];
            const domId = `board-workshop-${playerId}-${slotNumber}`;
            const el = document.getElementById(domId);
            if (el) return el;
            console.error(`[Meeples] Workshop slot element #${domId} not found`, meeple);
            return fallback;
        }

        // Apprentice idle slots: idle-apprentice-{1,2,3} → #idle-{playerId}-apprentice-{n}
        const apprenticeMatch = location.match(/^idle-apprentice-(\d+)$/);
        if (apprenticeMatch) {
            if (playerId === undefined) {
                console.error(`[Meeples] Apprentice location ${location} requires playerId`, meeple);
                return fallback;
            }
            const domId = `idle-${playerId}-apprentice-${apprenticeMatch[1]}`;
            const el = document.getElementById(domId);
            if (el) return el;
            console.error(`[Meeples] Apprentice idle element #${domId} not found`, meeple);
            return fallback;
        }

        // Idle player board (magician): idle-player-board → #idle-{playerId}-magician
        if (location === 'idle-player-board') {
            if (playerId === undefined) {
                console.error(`[Meeples] idle-player-board requires playerId`, meeple);
                return fallback;
            }
            const domId = `idle-${playerId}-magician`;
            const el = document.getElementById(domId);
            if (el) return el;
            console.error(`[Meeples] Idle element #${domId} not found`, meeple);
            return fallback;
        }

        // Specialist idle slots: idle-{type}-board → #idle-{playerId}-{type}
        const specialistMatch = location.match(/^idle-(manager|engineer|assistant)-board$/);
        if (specialistMatch) {
            if (playerId === undefined) {
                console.error(`[Meeples] ${location} requires playerId`, meeple);
                return fallback;
            }
            const specialistType = specialistMatch[1];
            // Assistant board has two slots: one for assistant, one for apprentice
            if (specialistType === 'assistant' && characterType === 'apprentice') {
                const domId = `idle-${playerId}-apprentice-assistant`;
                const el = document.getElementById(domId);
                if (el) return el;
                console.error(`[Meeples] Assistant-apprentice element #${domId} not found`, meeple);
                return fallback;
            }
            const domId = `idle-${playerId}-${specialistType}`;
            const el = document.getElementById(domId);
            if (el) return el;
            console.error(`[Meeples] Idle element #${domId} not found`, meeple);
            return fallback;
        }

        // Component player-board → first free component slot in magician board
        if (location === 'player-board') {
            if (playerId === undefined) {
                console.error(`[Meeples] Component location 'player-board' requires playerId`, meeple);
                return fallback;
            }
            const boardEl = document.getElementById(`magician-board-${playerId}`);
            if (!boardEl) {
                console.error(`[Meeples] Magician board #magician-board-${playerId} not found`, meeple);
                return fallback;
            }
            const slots = boardEl.querySelectorAll<HTMLElement>('.magician-tricks-components .component-slot');
            for (const slot of slots) {
                if (!slot.querySelector('.trickerion-meeple')) {
                    return slot;
                }
            }
            console.error(`[Meeples] No free component slot on player board ${playerId}`, meeple);
            return fallback;
        }

        // Component manager-board → first free manager component slot
        if (location === 'manager-board') {
            if (playerId === undefined) {
                console.error(`[Meeples] Component location 'manager-board' requires playerId`, meeple);
                return fallback;
            }
            const boardEl = document.getElementById(`magician-board-${playerId}`);
            if (!boardEl) {
                console.error(`[Meeples] Magician board #magician-board-${playerId} not found`, meeple);
                return fallback;
            }
            const slots = boardEl.querySelectorAll<HTMLElement>('.manager-workshop .component-slot');
            for (const slot of slots) {
                if (!slot.querySelector('.trickerion-meeple')) {
                    return slot;
                }
            }
            console.error(`[Meeples] No free manager component slot on player board ${playerId}`);
            return fallback;
        }

        // Trick marker locations — for now, use pending area
        if (location === 'available' || location === 'prepared' || location === 'scheduled') {
            const el = document.getElementById('trickerion-pending');
            if (el) return el;
            console.error(`[Meeples] #trickerion-pending not found for trick marker location ${location}`, meeple);
            return fallback;
        }

        // Unknown location — log and fall back
        console.error(`[Meeples] Unknown meeple location: "${location}"`, meeple);
        return fallback;
    },
};
