<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;

class GetShards extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_GET_SHARDS,
            type: StateType::GAME,
        );
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Get ${amount} shards (${sourceName})'),
                "args" => [
                    "amount" => $this->getNodeArgs("amount", 1),
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return [
            "log" => clienttranslate('Get ${amount} shards'),
            "args" => [
                "amount" => $this->getNodeArgs("amount", 1),
            ]
        ];
    }

    function onEnteringState(int $activePlayerId)
    {
        Players::get($activePlayerId)->addShards(1);
        return $this->resolve();
    }    
}