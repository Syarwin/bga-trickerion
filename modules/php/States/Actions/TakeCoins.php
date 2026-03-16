<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Models\Character;

class TakeCoins extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_TAKE_COINS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must take coins'),
            descriptionMyTurn: clienttranslate('${you} must take coins'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must take coins (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must take coins (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Take coins (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Take coins');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $sourceName = $this->getNodeArgs("sourceName");

        $availableCoins = Dice::getDice(Dice::DICE_TYPE_MONEY);
        
        $args = [
            "sourceName" => $sourceName,
            "availableCoins" => $availableCoins
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actTakeCoins(int $activePlayerId, int $coins)
    {
        Log::step();

        $availableCoins = $this->getActionArgs($activePlayerId)["availableCoins"];
        if (!in_array($coins, $availableCoins)) {
            throw new UserException("You cannot take this amount of coins");
        }

        $player = Players::get($activePlayerId);
        $player->addCoins($coins);

        $allDice = Dice::getDice(Dice::DICE_TYPE_MONEY);
        $dice = array_values(array_filter($allDice, function($die) use ($coins) {
            return $die == $coins || $die == Dice::ANY;
        }));
        Dice::setDieUnavailable(Dice::DICE_TYPE_MONEY, $dice[0]);

        return $this->resolve(["coins" => $coins]);
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
    function zombie(int $playerId) {
        
    }    
}