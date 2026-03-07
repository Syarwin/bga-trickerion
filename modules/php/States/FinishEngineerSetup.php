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
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class FinishEngineerSetup extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_FINISH_ENGINEER_SETUP,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $playersWithEngineers = Characters::getAll()
            ->where("location", Characters::LOCATION_IDLE_ANY)
            ->where("type", Character::TYPE_ENGINEER)
            ->pluck("playerId")
            ->toArray();

        if (empty($playersWithEngineers)) {
            return FinishSetup::class;
        }

        $turnOrder = Players::getTurnOrderFiltered($playersWithEngineers);

        return TurnOrderManager::launch(
            "engineerSetupTurn",
            $turnOrder,
            [self::class, 'startEngineerSetupTurn'],
            FinishSetup::class,
            false
        );
    }

    public static function startEngineerSetupTurn()
    {
        $node = [
            "state" => LearnTrick::class,
            "args" => [
                "sourceName" => clienttranslate('after selecting engineer')
            ]
        ];

        Engine::setup($node, ['order' => "engineerSetupTurn"]);
        return Engine::proceed();
    }
}