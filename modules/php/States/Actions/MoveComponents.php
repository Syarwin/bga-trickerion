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