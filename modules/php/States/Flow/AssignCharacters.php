<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Flow;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\NotificationMessage;
use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Constants\States;

class AssignCharacters extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct(
            $game,
            id: States::ST_ASSIGN_CHARACTERS,
            type: StateType::PRIVATE,
            descriptionMyTurn: clienttranslate('${you} must assign your characters'),
        );
    }

    function getArgs(int $playerId): array
    {
        $availableAssignmentCards = Assignments::getFiltered($playerId, Assignments::LOCATION_HAND);

        $assignedAssignments = Assignments::getFiltered($playerId, Assignments::LOCATION_ASSIGNED_ANY);
        $usedCharacterIds = $assignedAssignments->pluck("state")->toArray();

        // Also exclude characters that are already in pending assignments
        $pending = Globals::getPendingAssignments();
        $pendingForPlayer = $pending[$playerId] ?? [];
        $pendingCharacterIds = array_map(fn($e) => $e['characterId'], $pendingForPlayer);

        $unassignedCharacters = Characters::getFiltered($playerId, Characters::LOCATION_IDLE_ANY)
            ->whereNot("id", $usedCharacterIds)
            ->whereNot("id", $pendingCharacterIds);

        return [
            "availableAssignments" => $availableAssignmentCards->toArray(),
            "availableCharacters" => $unassignedCharacters->toArray(),
            "pendingAssignments" => $pendingForPlayer,
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

        // Store in global
        $pending = Globals::getPendingAssignments();
        $pending[$currentPlayerId][] = [
            'assignmentId' => $assignmentId,
            'characterId' => $characterId,
        ];
        Globals::setPendingAssignments($pending);

        // Private notification for the assigning player (with card data for animation)
        $this->notify->player($currentPlayerId, 'assignmentPending', clienttranslate('You assign ${assignment_name} to ${characterName}'), [
            'player_id' => $currentPlayerId,
            'characterName' => $character->getName(),
            'characterId' => $characterId,
            'assignment' => $assignment,
            'assignment_name' => $assignment->getName(),
            'i18n' => ['assignment_name'],
        ]);
        $this->game->gamestate->nextPrivateState($currentPlayerId, AssignCharacters::class);
    }

    #[PossibleAction]
    public function actUnassignCharacter(int $assignmentId, int $currentPlayerId)
    {
        $assignment = Assignments::get($assignmentId);

        if ($assignment->getPlayerId() !== $currentPlayerId) {
            throw new UserException("This is not your assignment");
        }

        // Remove from global
        $pending = Globals::getPendingAssignments();
        $playerPending = $pending[$currentPlayerId] ?? [];

        $found = false;
        foreach ($playerPending as $i => $entry) {
            if ($entry['assignmentId'] === $assignmentId) {
                array_splice($playerPending, $i, 1);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new UserException("This assignment is not in your pending list");
        }

        $pending[$currentPlayerId] = $playerPending;
        Globals::setPendingAssignments($pending);

        // Private notification to animate the card back
        $this->notify->player($currentPlayerId, 'unassignmentPending', clienttranslate('You unassign ${assignment_name}'), [
            'player_id' => $currentPlayerId,
            'characterId' => $assignment->getState(),
            'assignment' => $assignment,
            'assignment_name' => $assignment->getName(),
            'i18n' => ['assignment_name'],
        ]);
        $this->game->gamestate->nextPrivateState($currentPlayerId, AssignCharacters::class);
    }

    #[PossibleAction]
    public function actDone(int $currentPlayerId)
    {
        $this->notify->all('message', clienttranslate('${player_name} finished assigning the characters'), [
            'player_id' => $currentPlayerId,
        ]);
        $this->game->gamestate->setPlayerNonMultiactive($currentPlayerId, ResolveAssignments::class);
    }

    #[PossibleAction]
    public function actReset(int $currentPlayerId)
    {
        $pending = Globals::getPendingAssignments();
        $pending[$currentPlayerId] = [];
        Globals::setPendingAssignments($pending);

        $this->game->gamestate->nextPrivateState($currentPlayerId, AssignCharacters::class);
    }

    function zombie(int $playerId)
    {
        $this->actDone($playerId);
    }
}
