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

class MoveComponents extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MOVE_COMPONENTS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide which components to move'),
            descriptionMyTurn: clienttranslate('${you} must decide which components to move'),
        );
    }

    public function getDescription() {
        return clienttranslate('Move components');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "availableComponents" => Components::getFiltered($activePlayerId, Components::LOCATION_PLAYER_BOARD)->whereNot("count", 0)->toArray(),
            "usedSlots" => Components::getFiltered($activePlayerId, Components::LOCATION_MANAGER_BOARD)->toArray()
        ];
        return $args;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actMoveComponent(int $activePlayerId, array $args, int $componentId, ?int $toReplaceComponentId = null)
    {
        Log::step();

        $component = Components::get($componentId);

        if (!in_array($component, $args["availableComponents"])) {
            throw new UserException(clienttranslate("You cannot move this component"));
        }

        if (count($args["usedSlots"]) > 1 && $toReplaceComponentId === null) {
            throw new UserException(clienttranslate("You must choose a component to replace"));
        }

        $component->move($toReplaceComponentId);

        return $this->resolve(["componentId" => $componentId, "toReplaceComponentId" => $toReplaceComponentId]);
    }
}