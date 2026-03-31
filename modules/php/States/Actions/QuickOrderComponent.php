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

class QuickOrderComponent extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_QUICK_ORDER_COMPONENT,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must quick order components'),
            descriptionMyTurn: clienttranslate('${you} must quick order components'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must quick order components (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must quick order components (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Quick order components (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Quick order components');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableComponents = MarketRow::getComponentsForQuickOrder();
        
        $args = [
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
    public function actQuickOrderComponents(int $activePlayerId, array $args, string $component)
    {
        Log::step();
        if (!in_array($component, $args["availableComponents"])) {
            throw new UserException(clienttranslate("This component is not available for quick order"));
        }
        
        MarketRow::setQuickOrder($component);

        return $this->resolve(["component" => $component]);
    } 
}