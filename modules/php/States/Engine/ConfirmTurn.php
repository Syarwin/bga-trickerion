<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Engine;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;

class ConfirmTurn extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_CONFIRM_TURN,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must finalize their decisions.'),
            descriptionMyTurn: clienttranslate('${you} must finalize your decisions. When you do you won\'t be able to undo past this point'),
        );
    }

    public function onEnteringState(int $activePlayerId)
    {
        //TODO reimplement
        // // Check user preference to bypass if DISABLED is picked
        // $pref = Players::getActive()->getPref(Preferences::OPTION_CONFIRM);
        // if ($pref == Preferences::OPTION_DISABLED) {
        //     $this->actConfirmTurn();
        // }
    }

    #[PossibleAction]
    public function actConfirmTurn()
    {
        return Engine::confirm();
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