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
            "availableComponents" => Components::getFiltered($activePlayerId, Components::LOCATION_PLAYER_ANY)->whereNot("count", 0)->toArray()
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
        $availableIds = Collection::from($args["availableComponents"])->pluck("id")->toArray();
        
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
}