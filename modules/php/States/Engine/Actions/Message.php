<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Engine\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Game;

class Message extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MESSAGE,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        Game::get()->bga->notify->all("message", $this->getNodeArgs("message", ""), $this->getNodeArgs("args", []));
        return $this->resolve();
    }    
}