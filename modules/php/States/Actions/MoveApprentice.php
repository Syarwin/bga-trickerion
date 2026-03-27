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
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Models\Character;

class MoveApprentice extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MOVE_APPRENTICE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide which apprentice to move'),
            descriptionMyTurn: clienttranslate('${you} must decide which apprentice to move'),
        );
    }

    public function getCustomStateDescription() {
        $actionArgs = $this->getActionArgs(Players::getActiveId());

        if (!$actionArgs["isSlotAvailable"]) {
            return [
                "description" => clienttranslate('${actplayer} cannot move apprentice to assistant board'),
                "descriptionMyTurn" => clienttranslate('${you} cannot move apprentice to assistant board'),
            ];
        }
        return null;
    }

    public function getDescription() {
        return clienttranslate('Move apprentice');
    }

    public function isDoable($playerId) {
        $args = $this->getActionArgs($playerId);

        return $args["isSlotAvailable"] && count($args["availableApprentices"]) > 0;
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableApprentices = Characters::getFiltered($activePlayerId, Characters::LOCATION_IDLE_ANY, Character::TYPE_APPRENTICE)
            ->where("onAssistantBoard", false)
            ->toArray();

        $args = [
            "availableApprentices" => $availableApprentices,
            "isSlotAvailable" => Characters::isAssistantApprenticeSlotAvailable($activePlayerId),
        ];
        return $args;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actMoveApprentice(int $activePlayerId, array $args, int $apprenticeId)
    {
        Log::step();

        $apprentice = Characters::get($apprenticeId);
        //check if player has the apprentice
        if (!in_array($apprentice, $args["availableApprentices"])) {
            throw new UserException(clienttranslate("You don't have this apprentice"));
        }
        //check if there is available slot
        if (!$args["isSlotAvailable"]) {
            throw new UserException(clienttranslate("No available slot on assistant board"));
        }

        //move apprentice and its assigned assignment card to new location
        $apprentice->moveToAssistantBoard();

        return $this->resolve(["apprenticeId" => $apprenticeId]);
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