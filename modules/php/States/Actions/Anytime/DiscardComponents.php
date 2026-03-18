<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions\Anytime;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Component;

class DiscardComponents extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_DISCARD_COMPONENT,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide which components to discard'),
            descriptionMyTurn: clienttranslate('${you} must decide which components to discard'),
        );
    }

    public function getDescription() {
        return clienttranslate('Discard components');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "availableComponents" => Components::getAll()->where("playerId", $activePlayerId)->whereNot("count", 0)->toArray()
        ];
        return $args;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actDiscardComponent(int $activePlayerId, array $args, int $componentId)
    {
        Log::step();
        $availableIds = new Collection($args["availableComponents"]);
        $availableIds = $availableIds->pluck("id")->toArray();
        
        if (!in_array($componentId, $availableIds)) {
            throw new UserException(clienttranslate("You cannot discard this component"));
        }

        $component = Components::get($componentId);
        $component->setCount(0);
        $component->setLocation(Components::LOCATION_PLAYER_BOARD);

        Game::get()->bga->notify->all("componentDiscarded", clienttranslate('${player_name} discards all ${componentName}'), [
            "player_id" => $activePlayerId,
            "componentName" => Component::getComponentName($component->getType())
        ]);

        return $this->resolve(["discardComponentId" => $componentId]);
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