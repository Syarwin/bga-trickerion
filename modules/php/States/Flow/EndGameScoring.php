<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Flow;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\States\Engine\DummyEnd;

class EndGameScoring extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_END_GAME_SCORING,
            type: StateType::GAME,
        );
    }

    function onEnteringState()
    {
        //1 fame per shard
        Players::score('scoreShards', clienttranslate("Scoring 1 fame for each unspent shard"));
        //1 fame per 3 coins
        Players::score('scoreCoins', clienttranslate("Scoring 1 fame for each 3 unspent coins"));

        if (!Globals::isDarkAlley()) {
            //if no dark alley
            //2 fame for each apprentice
            Players::score('scoreApprentices', clienttranslate("Scoring 2 fame for each apprentice"));
            
            //3 fame for each specialist
            Players::score('scoreSpecialists', clienttranslate("Scoring 3 fame for each specialist"));
        } else {
            //if dark alley
            //2 fame for each special assignment
            Players::score('scoreSpecialAssignments', clienttranslate("Scoring 2 fame for each special assignment"));
            
            //level 3 tricks
            Players::score('scoreTricks', clienttranslate("Scoring level 3 tricks"), false);
        }

        return DummyEnd::class;
    }
}