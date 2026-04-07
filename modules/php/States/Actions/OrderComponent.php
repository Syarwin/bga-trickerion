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
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;

class OrderComponent extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_ORDER_COMPONENT,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must order components'),
            descriptionMyTurn: clienttranslate('${you} must order components'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must order components (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must order components (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Order components (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Order components');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableComponents = MarketRow::getOrderableComponents();
        $availableOrderSlots = MarketRow::getEmptyOrderSlots();

        $args = [
            "availableOrderSlots" => $availableOrderSlots,
            "availableComponents" => $availableComponents
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actOrderComponents(int $activePlayerId, array $args, string $component, int $slotId)
    {
        Log::step();
        if (!in_array($component, $args["availableComponents"])) {
            throw new UserException(clienttranslate("This component is not available to order"));
        }
        if (!in_array($slotId, $args["availableOrderSlots"])) {
            throw new UserException(clienttranslate("This order slot is not available"));
        }

        MarketRow::addToOrder($component, $slotId);

        return $this->resolve(["component" => $component, "slotId" => $slotId]);
    }
}