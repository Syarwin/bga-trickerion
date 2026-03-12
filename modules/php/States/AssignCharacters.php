<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\States;
use Bga\Games\trickerionlegendsofillusion\States\PlaceCharacters;

class AssignCharacters extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_ASSIGN_CHARACTERS,
            type: StateType::PRIVATE,
            descriptionMyTurn: clienttranslate('${you} must assign your characters'),
        );
    }

    function getArgs(int $playerId): array  {
        $availableAssignmentCards = Assignments::getFiltered($playerId, Assignments::LOCATION_HAND);
        
        $assignedAssignments = Assignments::getFiltered($playerId, Assignments::LOCATION_ASSIGNED_ANY);
        $usedCharacterIds = $assignedAssignments->pluck("state")->toArray();

        $unassignedCharacters = Characters::getFiltered($playerId, Characters::LOCATION_IDLE_ANY)
            ->whereNot("id", $usedCharacterIds);

        return [
            "availableAssignments" => $availableAssignmentCards->toArray(),
            "availableCharacters" => $unassignedCharacters->toArray()
        ];
    }

    #[PossibleAction] 
    public function actAssignCharacter(int $assignmentId, int $characterId, array $args, int $currentPlayerId)
    {       
        $assignment = Assignments::get($assignmentId);
        $character = Characters::get($characterId);

        if (!in_array($assignment, $args["availableAssignments"])) {
            throw new UserException("This assignment is not available");
        }

        if (!in_array($character, $args["availableCharacters"])) {
            throw new UserException("This character is not available");
        }

        $assignment->assignToCharacter($character);
        $this->game->gamestate->nextPrivateState($currentPlayerId, self::class);
    }
    
    #[PossibleAction] 
    public function actAssignCharacters(#[IntArrayParam()] $assignmentIds, #[IntArrayParam()] $characterIds, array $args, int $currentPlayerId)
    {
        if (count($assignmentIds) !== count($characterIds)) {
            throw new UserException("You must assign the same number of characters and assignments");
        }

        for ($i = 0; $i < count($assignmentIds); $i++) {
            $this->actAssignCharacter($assignmentIds[$i], $characterIds[$i], $args, $currentPlayerId);
        }

        $this->game->gamestate->setPrivateState($currentPlayerId, self::class);
    }

    #[PossibleAction] 
    public function actDone(int $currentPlayerId)
    {
        Game::get()->bga->notify->all('message', clienttranslate('${player_name} finished assigning the characters'), [
            'player_id' => $currentPlayerId,
        ]);
        $this->game->gamestate->setPlayerNonMultiactive($currentPlayerId, PlaceCharacters::class);
    }
    
    #[PossibleAction] 
    public function actReset(int $currentPlayerId)
    {
        Assignments::resetAssignments($currentPlayerId);
        $this->game->gamestate->nextPrivateState($currentPlayerId, self::class);
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