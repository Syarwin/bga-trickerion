<?php

declare(strict_types=1);

namespace Bga\Games\Trickerion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\Trickerion\Framework\Engine\Engine;
use Bga\Games\Trickerion\Game;
use Bga\Games\Trickerion\States\Constants\States;

class PlayerTurn extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_PLAYER_TURN,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $this->game->giveExtraTime($activePlayerId);
        $newNode = [
            "playerId" => $activePlayerId,
            "type" => Engine::NODE_SEQUENTIAL,
            "children" => [
                [
                    "type" => Engine::NODE_PARALLEL,
                    "children" => [
                        [
                            // "state" => DrawOrPair::class,
                            "args" => [
                            ]
                        ]
                    ],
                ]
            ]
        ];

        Engine::setup($newNode, [/*'state' => EndTurn::class*/]);
        return Engine::proceed();

    }    
}