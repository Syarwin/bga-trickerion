<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;

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
    public function actBuyComponents(int $activePlayerId, array $args, string $component, string $locationId, int $count, int $bargain)
    {
        Log::step();
        if (!array_key_exists($component, $args["availableComponents"])) {
            throw new UserException(clienttranslate("This component is not available to buy"));
        }

        if (!array_key_exists($locationId, $args["availableComponents"][$component])) {
            throw new UserException(clienttranslate("You cannot place this component at this location"));
        }

        if ($count < 1 || $count > $args["availableComponents"][$component][$locationId]["max"]) {
            throw new UserException(clienttranslate("Invalid count"));
        }

        $cost = $args["availableComponents"][$component][$locationId]["effectiveCost"] * $count;
        if ($bargain > $args["remainingActionPoints"] || $bargain > $cost) {
            throw new UserException(clienttranslate("Bargain is not correct"));
        }

        Components::addComponent($activePlayerId, $component, $locationId, $count, $bargain);
        return $this->resolve(["component" => $component, "locationId" => $locationId, "count" => $count]);
    }    
}