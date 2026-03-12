<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\States;

class PlaceCharacter extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PLACE_CHARACTER,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must place a character'),
            descriptionMyTurn: clienttranslate('${you} must place a character'),
        );
    }

    private function isSkipped() {
        return count($this->getActionArgs(Players::getActiveId())["availableAssignments"]) == 0;
    }

    public function onEnteringState(int $activePlayerId, array $args)
    {
        $remainingUnassignedCharacters = count(Assignments::getAvailableAssignments());
        if ($remainingUnassignedCharacters == 0) {
            $this->bga->notify->all("message", clienttranslate('Players placed all of their characters.'), []);
            return TurnOrderManager::end("turn");
        }

        if ($this->isSkipped()) {
            $this->bga->notify->all("message", clienttranslate('${player_name} has no available characters and is skipped.'), [
                "player_id" => $activePlayerId,
            ]);

            return $this->resolve(["skipped" => true, "automatic" => true]);
        }
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableAssignments = Assignments::getAvailableAssignments($activePlayerId);
        $args = [
            "availableAssignments" => $availableAssignments
        ];
        return $args;
    }
    
    #[PossibleAction]
    public function actPlace(int $characterId, string $locationId, array $args, int $activePlayerId)
    {
        $character = Characters::get($characterId);

        if ($character->getPlayerId() !== $activePlayerId) {
            throw new UserException("You can only place your own characters");
        }

        $assignment = Assignments::getFiltered($activePlayerId, Assignments::LOCATION_ASSIGNED_FACEUP)
            ->where("state", $characterId)
            ->first();

        if (is_null($assignment)) {
            throw new UserException("You can only place a character that is currently assigned faceup");
        }

        if (!in_array($locationId, $character->getPossibleLocations($assignment->getBoardLocation()))) {
            throw new UserException("This character cannot be placed on this location");
        }

        $character->setLocation($locationId);

        $this->bga->notify->all("characterPlaced", clienttranslate('${player_name} places ${character} on ${locationName} (+${actionPoints})'), [
            "player_id" => $activePlayerId,
            "character" => Characters::get($characterId),
            "locationId" => $locationId,
            "locationName" => $locationId,
            "actionPoints" => 1
        ]);

        return $this->resolve(["characterId" => $characterId, "locationId" => $locationId]);
    }

    #[PossibleAction]
    public function actLeaveIdle(int $characterId, int $activePlayerId, array $args)
    {
        $assignment = Assignments::getFiltered($activePlayerId, Assignments::LOCATION_ASSIGNED_FACEUP)
            ->where("state", $characterId)
            ->first();

        if (is_null($assignment)) {
            throw new UserException("You can only leave idle a character that is currently assigned faceup");
        }

        $assignment->setLocation(Assignments::LOCATION_ASSIGNED_FACEDOWN);
            
        $this->bga->notify->all("characterIdled", clienttranslate('${player_name} leaves ${character} idle'), [
            "player_id" => $activePlayerId,
            "character" => Characters::get($characterId),
            "assignment" => $assignment
        ]);

        return $this->resolve(["idleCharacterId" => $characterId]);
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