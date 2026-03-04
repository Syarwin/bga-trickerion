<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class DummyEnd extends \Bga\GameFramework\States\GameState
{

    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_DUMMY_END,
            type: StateType::MULTIPLE_ACTIVE_PLAYER,
            description: clienttranslate('Please report any possible end of the game bugs and click finish to end the game'),
            descriptionMyTurn: clienttranslate('Please report any possible end of the game bugs and click finish to end the game'),
        );
    }

    /**
     * Game state action, example content.
     *
     * The onEnteringState method of state `EndScore` is called just before the end of the game.
     */
    public function onEnteringState() {
        Game::get()->gamestate->setAllPlayersMultiactive();
    }

    #[PossibleAction]
    public function actEnd()
    {
        return States::ST_END_GAME;
    }

    /**
     * This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
     * You can do whatever you want in order to make sure the turn of this player ends appropriately
     * (ex: play a random card).
     * 
     * See more about Zombie Mode: https://en.doc.boardgamearena.com/Zombie_Mode
     *
     * Important: your zombie code will be called when the player leaves the game. This action is triggered
     * from the main site and propagated to the gameserver from a server, not from a browser.
     * As a consequence, there is no current player associated to this action. In your zombieTurn function,
     * you must _never_ use `getCurrentPlayerId()` or `getCurrentPlayerName()`, 
     * but use the $playerId passed in parameter and $this->game->getPlayerNameById($playerId) instead.
     */
    public function zombie(int $playerId) {// Example of zombie level 0: return NextPlayer::class; or $this->actPass($playerId);

        // // Example of zombie level 1:
        // $args = $this->getArgs();
        // $zombieChoice = $this->getRandomZombieChoice($args['playableCardsIds']); // random choice over possible moves
        // return $this->actPlayCard($zombieChoice, $playerId, $args); // this function will return the transition to the next state
        // 
    }
}