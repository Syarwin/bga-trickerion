<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Flow;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Managers\Posters;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;
use Bga\Games\trickerionlegendsofillusion\Managers\Prophecies;

class EndTurnPhase extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_END_TURN_PHASE,
            type: StateType::GAME,
        );
    }

    function onEnteringState()
    {
        Characters::payWages();
        Characters::return();
        
        MarketRow::ordersArrive();
        MarketRow::clearQuickOrder();
        
        Performances::roundMaintenenace();

        Posters::return();

        Prophecies::roundMaintenance();

        Assignments::roundMaintenance();
        
        $currentTurn = Globals::getCurrentTurn();

        if (Globals::isDarkAlley() && $currentTurn == 7
            || !Globals::isDarkAlley() && $currentTurn == 5)
        {
            return EndGameScoring::class;
        }

        Globals::incCurrentTurn(1);

        return TurnPreparation::class;
    }
}