<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class TurnPreparation extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_TURN_PREPARATION,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        //roll dice
        //adjust initiative

        //start avertising turn

        return TurnOrderManager::lauchDefault("turn", AdvertiseTurn::class, StartAssignment::class, false);
    }    
}