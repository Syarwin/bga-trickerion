<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Flow;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Constants\States;

class ResolveAssignments extends GameState
{
  function __construct(
    protected Game $game,
  ) {
    parent::__construct(
      $game,
      id: States::ST_RESOLVE_ASSIGNMENTS,
      type: StateType::GAME,
    );
  }

  function onEnteringState()
  {
    $pending = Globals::getPendingAssignments();

    foreach ($pending as $playerId => $entries) {
      foreach ($entries as $entry) {
        $assignment = Assignments::get($entry['assignmentId']);
        $character = Characters::get($entry['characterId']);

        // This calls assignment->assignToCharacter($character) which does
        // the actual DB writes and fires the assignmentAssigned notification
        $assignment->assignToCharacter($character);
      }
    }

    // Clear the global
    Globals::setPendingAssignments([]);

    // Transition to PlaceCharacters
    $this->game->gamestate->nextState(PlaceCharacters::class);
  }
}
