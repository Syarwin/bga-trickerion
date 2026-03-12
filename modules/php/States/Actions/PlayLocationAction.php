<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;

class PlayLocationAction extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PLAY_LOCATION_ACTIONS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must play location actions (remaining AP: ${remainingAps})'),
            descriptionMyTurn: clienttranslate('${you} must play location actions (remaining AP: ${remainingAps})'),
        );
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [];
        $args["availableActions"] = LocationActions::getActions($activePlayerId);
        $args["remainingAps"] = LocationActions::getRemainingActionPoints();
        return $args;
    }    

    public function actPlayAction(int $activePlayerId, array $args, string $actionId)
    {
        Log::step();
        LocationActions::playAction($activePlayerId, $actionId);

        if (LocationActions::getRemainingActionPoints() > 0) {
            Engine::insertAsSibling([
                "state" => PlayLocationAction::class
            ]);
        }
            
        return $this->resolve(["actionId" => $actionId]);
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