<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class PlaceCharacters extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_PLACE_CHARACTERS,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        //reveal characters
        $revealedAssignments = Assignments::getInLocation(Assignments::LOCATION_ASSIGNED_FACEDOWN)
            ->update("location", Assignments::LOCATION_ASSIGNED_FACEUP);

        $this->notify->all("assignmentsRevealed", clienttranslate('All assignment cards are revealed'), [
            "assignments" => $revealedAssignments->toArray(),
        ]);

        //start initiative turn order
        return TurnOrderManager::launch("turn", Players::getOrderByInitiative(), [self::class, "startPlaceCharacterTurn"], StartAssignment::class, true);
    }

    public static function startPlaceCharacterTurn()
    {
        Game::get()->giveExtraTime(Players::getActiveId());
        $node = [
            "state" => PlaceCharacter::class
        ];

        Engine::setup($node, ["order" => "turn"]);
        return Engine::proceed();
    }
}