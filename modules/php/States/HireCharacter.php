<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\States;

class HireCharacter extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_HIRE_CHARACTER,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must hire a character'),
            descriptionMyTurn: clienttranslate('${you} must hire a character'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must hire a character (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must hire a character (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Hire a character (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Hire a character');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $sourceName = $this->getNodeArgs("sourceName");

        $types = $this->getNodeArgs("types", null);

        $availableCharacters = Characters::getFiltered($activePlayerId, Characters::LOCATION_SUPPLY);

        if (!is_null($types)) {
            $availableCharacters = $availableCharacters->where("type", $types);
        }

        $availableCharacterTypes = $availableCharacters->pluck('type')->toArray(); 
        $availableCharacterTypes = array_unique($availableCharacterTypes);

        $args = [
            "sourceName" => $sourceName,
            "availableCharacterTypes" => $availableCharacterTypes
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actHireCharacter(int $activePlayerId, string $characterType)
    {
        Log::step();

        $availableCharacterTypes = $this->getActionArgs($activePlayerId)["availableCharacterTypes"];
        if (!in_array($characterType, $availableCharacterTypes)) {
            throw new UserException("You cannot hire this character");
        }

        $location = $this->getNodeArgs("location", Characters::LOCATION_IDLE_PLAYER_BOARD);
        Characters::hire($characterType, $activePlayerId, $location);

        return $this->resolve(["type" => $characterType]);
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