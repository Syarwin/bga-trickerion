<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class BuyComponents extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_BUY_COMPONENTS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must buy components'),
            descriptionMyTurn: clienttranslate('${you} must buy components'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must buy components (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must buy components (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Buy components (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Buy components');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $buyableComponents = MarketRow::getBuyableComponents();

        $args = [
            "remainingActionPoints" => LocationActions::getRemainingActionPoints(),
            "availableComponents" => Components::getMaxCounts($buyableComponents, $activePlayerId)
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actBuyComponents(int $activePlayerId, array $args, string $component, string $locationId, int $count)
    {
        Log::step();
        if (!array_key_exists($component, $args["availableComponents"])) {
            throw new UserException(clienttranslate("This component is not available to buy"));
        }

        if (!array_key_exists($locationId, $args["availableComponents"][$component])) {
            throw new UserException(clienttranslate("You cannot place this component at this location"));
        }

        if ($count < 1 || $count > $args["availableComponents"][$component][$locationId]) {
            throw new UserException(clienttranslate("Invalid count"));
        }

        Components::addComponent($activePlayerId, $component, $locationId, $count);
        return $this->resolve(["component" => $component, "locationId" => $locationId, "count" => $count]);
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