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
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;
use Bga\Games\trickerionlegendsofillusion\States\Engine\DummyEnd;

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

        $this->notify->all("message", clienttranslate('New prophecy is revealed'), []);
        $this->notify->all("message", clienttranslate('Assinment cards are returned to hand and played special assignments are discarded'), []);
        
        $currentTurn = Globals::getCurrentTurn();

        if (Globals::isDarkAlley() && $currentTurn == 7
            || !Globals::isDarkAlley() && $currentTurn == 5)
        {
            return DummyEnd::class;
        }

        Globals::incCurrentTurn(1);

        return TurnPreparation::class;
    }
}