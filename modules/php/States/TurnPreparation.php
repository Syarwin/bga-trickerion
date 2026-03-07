<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
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

        //start advertising turn
        return TurnOrderManager::launchDefault("turn", [self::class, "startAdvertiseTurn"], StartAssignment::class, false);
    }

    public static function startAdvertiseTurn() {
        Game::get()->giveExtraTime(Players::getActiveId());
        $newNode = [
            "state" => Advertise::class,
            "args" => [
            ]
        ];

        Engine::setup($newNode, ["order" => "turn"]);
        return Engine::proceed();
    }
}