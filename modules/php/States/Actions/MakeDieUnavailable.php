<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;

class MakeDieUnavailable extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MAKE_DIE_UNAVAILABLE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose which die to turn to "X"'),
            descriptionMyTurn: clienttranslate('${you} must choose which die to turn to "X"'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must choose which die to turn to "X" (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must choose which die to turn to "X" (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Choose which die to turn to "X" (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Choose which die to turn to "X"');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $dice = Dice::getDice($this->getNodeArgs("dieType"));
        $args = [
            "availableDice" => $dice,
            "sourceName" => $this->getNodeArgs("sourceName")
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actMakeDieUnavailable(int $activePlayerId, string $dieFace, array $args)
    {
        Log::step();

        if (!in_array($dieFace, $args["availableDice"])) {
            throw new UserException(clienttranslate("This die is not available"));
        }

        Dice::setDieUnavailable($this->getNodeArgs("dieType"), $dieFace);
        
        return $this->resolve(["dieFace" => $dieFace]);
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