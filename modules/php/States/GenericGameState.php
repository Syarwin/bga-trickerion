<?php

declare(strict_types=1);

namespace Bga\Games\Trickerion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\Trickerion\Framework\Engine\Constants\States;
use Bga\Games\Trickerion\Game;

class GenericGameState extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_GENERIC_GAME_STATE,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId) {
    }
}