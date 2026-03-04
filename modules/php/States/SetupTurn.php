<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class SetupTurn extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_SETUP_TURN,
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
                            "state" => ChooseMagician::class,
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