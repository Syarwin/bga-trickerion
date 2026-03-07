<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Prophecies;
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
        $node = [
            "type" => Engine::NODE_SEQUENTIAL,
            "children" => Players::getAll()->map(fn($player) => [
                "state" => PrepareTrick::class,
                "playerId" => $player->id,
                "args" => [
                    "auto" => true
                ]
            ])->toArray()
        ];

        Engine::setup($node, [self::class, "setupPendingProphecies"]);
        return Engine::proceed();
    }

    public static function setupPendingProphecies() {
        if (Globals::isIncludeProphecies()) {
            $pendingProphecies = Prophecies::getTopOf(Prophecies::LOCATION_DECK, 3);
            Prophecies::insertOnTop($pendingProphecies->getIds(), Prophecies::LOCATION_PENDING);
            Game::get()->bga->notify->all("pendingProphecies", clienttranslate('Pending prophecioes are revealed'), [
                "prophecies" => $pendingProphecies->toArray()
            ]);
        }

        return TurnPreparation::class;
    }
}