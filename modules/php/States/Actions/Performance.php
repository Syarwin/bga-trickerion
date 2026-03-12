<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;

class Performance extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PERFORMANCE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose a performance to perform for ${day}'),
            descriptionMyTurn: clienttranslate('${you} must choose a performance to perform for ${day}'),
        );
    }

    public function onEnteringState() {
        $skip = $this->getNodeArgs("skip") ?? false;
        if ($skip) {
            $this->bga->notify->all("message", clienttranslate('No player is performing for ${day}, skipping'), [
                "day" => $this->getNodeArgs("day"),
            ]);

            return $this->resolve(["skipped" => true, "automatic" => true]);
        }
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "day" => $this->getNodeArgs("day"),
            "availablePerformances" => Performances::getInLocation(Performances::LOCATION_ACTIVE)->toArray()
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actSelectPerformance(int $activePlayerId, int $performanceId)
    {
        Log::step();
        $this->bga->notify->all("message", clienttranslate('${player_name} chooses performance ${performance}'), [
            "player_id" => $activePlayerId,
            "performance" => Performances::get($performanceId),
        ]);
        return $this->resolve(["performanceId" => $performanceId]);
    }

    /**
     * This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
     * You can do whatever you want in order to make sure the turn of this player ends appropriately
     * (ex: play a random card).
     * 
     * See more about Zombie Mode: https://en.doc.boardgamearena.com/Zombie_Mode
     *
     * Important: your zombie code will be called when the player leaves the game. This action is triggered
     * from the main site and propagated to the gameserver from a server, not from a browser.
     * As a consequence, there is no current player associated to this action. In your zombieTurn function,
     * you must _never_ use `getCurrentPlayerId()` or `getCurrentPlayerName()`, 
     * but use the $playerId passed in parameter and $this->game->getPlayerNameById($playerId) instead.
     */
    function zombie(int $playerId) {
        
    }    
}