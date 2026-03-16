<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;

class EnhanceCharacter extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_ENHANCE_CHARACTER,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        Players::get($activePlayerId)->incShards(-1);
        LocationActions::incActionPoints(1);
        
        Game::get()->bga->notify->all("shardsChanged", clienttranslate('${player_name} spends 1 shard to enhance a character (+1 AP)'), [
            "player_id" => $activePlayerId,
        ]);

        return $this->resolve();
    }    
}