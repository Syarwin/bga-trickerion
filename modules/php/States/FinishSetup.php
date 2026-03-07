<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class FinishSetup extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_FINISH_SETUP,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        

        return TurnPreparation::class;
    }    
}