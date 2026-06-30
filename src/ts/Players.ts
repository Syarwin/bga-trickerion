import { getAnimationManager } from './libLoader';
import { addUpdatePlayerOrderingCallback, getCurrentPlayerId } from './framework/utils';
import { formatIcon } from './format';

export class Players {
    game: any;
    bga: ExtendedBga;
    gamedatas: TrickerionGamedatas | null = null;
    private counters: Map<string, Counter> = new Map();

    init(gamedatas: TrickerionGamedatas, game: any, bga: ExtendedBga) {
        this.game = game;
        this.bga = bga;
        this.gamedatas = gamedatas;

        for (const playerId in this.gamedatas.players) {
            const player = this.gamedatas.players[playerId];

            // Setup counters
            this.setupPlayerCounters(parseInt(playerId));

            // Set initial counter values
            const coinCounter = this.getCounter(parseInt(playerId), 'coin');
            const shardCounter = this.getCounter(parseInt(playerId), 'shard');

            if (coinCounter) coinCounter.setValue(player.coins);
            if (shardCounter) shardCounter.setValue(player.shards);
        }

        // Setup player panel ordering based on initiative
        this.updatePlayerPanelOrdering();

        // Register callback for when player ordering needs to be updated
        addUpdatePlayerOrderingCallback(() => this.updatePlayerPanelOrdering());
    }

    /**
     * Create and initialize counters for a player's coins and shards
     */
    private setupPlayerCounters(playerId: number): void {
        const panelElement = this.bga.playerPanels.getElement(playerId);

        // Create counters with proper IDs matching animation targets
        panelElement.insertAdjacentHTML(
            'afterbegin',
            `
<div class="player-info">
  <div class="player-coins">
    <span id="counter-${playerId}-coin"></span>
    ${formatIcon('coin')}
  </div>
  <div class="player-shards">
    <span id="counter-${playerId}-shard"></span>
    ${formatIcon('shard')}
  </div>
</div>
`
        );

        // Create counter for coins
        const coinCounter = new ebg.counter();
        coinCounter.create(`counter-${playerId}-coin`, { value: 0 });
        this.counters.set(`coin-${playerId}`, coinCounter);

        // Create counter for shards
        const shardCounter = new ebg.counter();
        shardCounter.create(`counter-${playerId}-shard`, { value: 0 });
        this.counters.set(`shard-${playerId}`, shardCounter);
    }

    /**
     * Get a player's counter by type
     */
    private getCounter(playerId: number, type: 'coin' | 'shard'): Counter | null {
        return this.counters.get(`${type}-${playerId}`) || null;
    }

    /**
     * Animate a player counter receiving/losing resources (coins or shards)
     * Uses bga-animations module for sliding
     */
    async animatePlayerCounter(playerId: number, type: 'coin' | 'shard', n: number): Promise<void> {
        const player = this.gamedatas!.players[playerId];
        if (!player) return;

        let counter = this.getCounter(playerId, type);

        // Get current value from counter
        const currentValue = counter ? counter.getValue() : type === 'coin' ? player.coins : player.shards;
        const newValue = currentValue + n;

        // If no change, resolve immediately
        if (currentValue === newValue) {
            return Promise.resolve();
        }

        const counterId = `counter-${playerId}-${type}`;
        const animationId = `animation-${type}`;
        const titleContainer = document.getElementById('pagemaintitletext') || document.createElement('div');

        const animationManager = await getAnimationManager();

        // Create temporary animation element with the icon
        const icon = formatIcon(type, Math.abs(n));
        titleContainer.insertAdjacentHTML('beforebegin', `<div id='${animationId}' class="animation-elt">${icon}</div>`);

        const counterElement = document.getElementById(counterId) as HTMLElement;
        const slidingDuration = 1200;

        if (n < 0) {
            // Losing resources: update counter first, then slide from counter to title
            if (counter) {
                counter.toValue(newValue);
            }

            await animationManager.slideFloatingElement($(animationId), counterElement, titleContainer, {
                duration: slidingDuration,
                fromPlaceholder: 'off',
                toPlaceholder: 'off',
            });
        } else {
            // Gaining resources: slide from title to counter, then update counter
            await animationManager.slideFloatingElement($(animationId), titleContainer, counterElement, {
                duration: slidingDuration,
                fromPlaceholder: 'off',
                toPlaceholder: 'off',
            });

            if (counter) {
                counter.toValue(newValue);
            }
        }
    }

    /**
     * Update player panel ordering based on initiative, with current player always first
     */
    updatePlayerPanelOrdering(): void {
        if (!this.gamedatas) return;

        const players = this.gamedatas.players;
        const playerIds = Object.keys(players).map(Number);
        const currentPlayerId = getCurrentPlayerId();

        if (playerIds.length <= 1) return;

        // Sort players by initiative (higher initiative goes first)
        playerIds.sort((a, b) => {
            // Current player always comes first
            if (a === currentPlayerId) return -1;
            if (b === currentPlayerId) return 1;

            // Then sort by initiative (descending - higher initiative is better)
            const initiativeA = players[a]?.initiative ?? 0;
            const initiativeB = players[b]?.initiative ?? 0;
            return initiativeB - initiativeA; // Higher initiative first
        });

        // Reorder the player panels in the DOM
        const playerBoards = document.getElementById('player_boards');
        if (!playerBoards) return;

        // Get all player board elements
        const playerBoardElements = Array.from(playerBoards.querySelectorAll('.player-board'));

        // Sort the elements based on the new order
        playerBoardElements.sort((elA, elB) => {
            const idA = parseInt(elA.getAttribute('data-player-id') || elA.id.replace('player_board_', ''));
            const idB = parseInt(elB.getAttribute('data-player-id') || elB.id.replace('player_board_', ''));
            return playerIds.indexOf(idA) - playerIds.indexOf(idB);
        });

        // Re-append elements in the new order
        playerBoardElements.forEach((el) => {
            playerBoards.appendChild(el);
        });

        // Update data-order attributes for visual indication
        playerIds.forEach((playerId, index) => {
            document.getElementById(`overall_player_board_${playerId}`).setAttribute('data-order', String(index + 1));
        });
    }

    /**
     * Handle coinsChanged notification
     */
    async onCoinsChanged(args: CoinsChangedArgs): Promise<void> {
        const playerId = args.player_id;
        const delta = args.coins;
        const newValue = args.newValue;

        // Update gamedatas
        if (this.gamedatas && this.gamedatas.players[playerId]) {
            this.gamedatas.players[playerId].coins = newValue;
        }

        // Animate the change
        await this.animatePlayerCounter(playerId, 'coin', delta);
    }

    /**
     * Handle shardsChanged notification
     */
    async onShardsChanged(args: ShardsChangedArgs): Promise<void> {
        const playerId = args.player_id;
        const delta = args.shards;
        const newValue = args.newValue;

        // Update gamedatas
        if (this.gamedatas && this.gamedatas.players[playerId]) {
            this.gamedatas.players[playerId].shards = newValue;
        }

        // Animate the change
        await this.animatePlayerCounter(playerId, 'shard', delta);
    }

    /**
     * Handle initiativeAdjusted notification
     */
    async onInitiativeAdjusted(args: InitiativeAdjustedArgs): Promise<void> {
        const newInitiatives = args.newInitiatives;

        // Update each player's initiative
        for (const playerId in newInitiatives) {
            const initiative = newInitiatives[playerId];
            this.gamedatas.players[playerId].initiative = initiative;
        }

        this.updatePlayerPanelOrdering();
    }
}

export const players = new Players();
