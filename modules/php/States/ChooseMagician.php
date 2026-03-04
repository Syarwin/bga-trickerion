<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class ChooseMagician extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_CHOOSE_MAGICIAN,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose a magician'),
            descriptionMyTurn: clienttranslate('${you} must choose a magician'),
        );
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actChooseMagician(int $activePlayerId, int $magicianId)
    {
        
        return $this->resolve(["magicianId" => $magicianId]);
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