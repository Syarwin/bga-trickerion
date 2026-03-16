<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Prophecies;

class FortuneTelling extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_FORTUNE_TELLING,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $UpdatedProphecies = Prophecies::getInLocation(Prophecies::LOCATION_PENDING)
            ->forEach(function ($prophecy) {
                $prophecy->incState(-1);

                if ($prophecy->getState() <= 0) {
                    $prophecy->setState(3);
                }
            });

        Game::get()->bga->notify->all("propheciesUpdated", clienttranslate('${player_name} use fortune telling and rotated all pending prophecies clockwise'), [
            "player_id" => $activePlayerId,
            "updatedProphecies" => $UpdatedProphecies->toArray(),
        ]);

        return $this->resolve();
    }    
}