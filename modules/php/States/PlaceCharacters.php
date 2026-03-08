<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
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
        //start initiative turn order
        return TurnOrderManager::launch("turn", Players::getOrderByInitiative(), [self::class, "startPlaceCharacterTurn"], StartAssignment::class, true);
    }
}