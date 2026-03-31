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
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;

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
}