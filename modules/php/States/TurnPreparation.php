<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
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
        $turn = Globals::getCurrentTurn();
        $this->notify->all("message", clienttranslate('Turn ${turn} begins'), ["turn" => $turn]);

        //adjust initiative
        $turn = Globals::getCurrentTurn();
        if ($turn > 1) {
            Players::adjustInitiative();
        }

        //roll dice
        Dice::roll();

        //start advertising turn
        return TurnOrderManager::launch("turn", Players::getOrderByInitiative(), [self::class, "startAdvertiseTurn"], StartAssignment::class, false);
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