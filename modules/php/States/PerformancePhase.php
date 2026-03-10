<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class PerformancePhase extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_PERFORMANCE_PHASE,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $this->notify->all("message", clienttranslate('Performance Phase begins'), []);

        $performances = Characters::getPerformingPlayers();

        $node = [
            "type" => Engine::NODE_SEQUENTIAL,
            "children" => array_map(function($day) use ($performances) {
                return [
                    "state" => Performance::class,
                    "playerId" => $performances[$day] ?? null,
                    "args" => [ 
                        "day" => $day,
                        "skip" => !isset($performances[$day]),
                    ]
                ];
            }, ["thursday", "friday", "saturday", "sunday"]),
        ];

        Engine::setup($node, StartAssignment::class);
        return Engine::proceed();
    }
}