<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;

class PlayLocationAction extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PLAY_LOCATION_ACTIONS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must play location actions (remaining AP: ${remainingAps})'),
            descriptionMyTurn: clienttranslate('${you} must play location actions (remaining AP: ${remainingAps})'),
        );
    }

    public function isOptional() {
        return true;
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [];
        $args["availableActions"] = LocationActions::getActions($activePlayerId);
        $args["remainingAps"] = LocationActions::getRemainingActionPoints();
        return $args;
    }    

    public function onEnteringState(array $args, int $activePlayerId) {
        if (count($args["availableActions"]) == 0) {
            return $this->resolve([
                "automatic" => true
            ]);
        }
    }

    #[PossibleAction]
    public function actPlayAction(int $activePlayerId, array $args, string $actionId)
    {
        Log::step();
        LocationActions::playAction($activePlayerId, $actionId);

        Engine::insertAsSibling([
            "state" => PlayLocationAction::class
        ]);
            
        return $this->resolve(["actionId" => $actionId]);
    }
}