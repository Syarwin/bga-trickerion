<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AutomaticActionState;

class HiredCharacterSetup extends AutomaticActionState
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_HIRED_CHARACTER_SETUP,
            type: StateType::GAME,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $specialistType = Characters::getFiltered($activePlayerId, Characters::LOCATION_IDLE_ANY)
            ->where("specialist", true)
            ->first()
            ->getType();

        if ($specialistType === Character::TYPE_ASSISTANT) {
            Characters::hire(Character::TYPE_APPRENTICE, $activePlayerId, Characters::LOCATION_IDLE_ASSISTANT_BOARD);
        } elseif ($specialistType === Character::TYPE_ENGINEER) {
            //nothing now, but after all initial magicians and tricks are taken
            Game::get()->bga->notify->all("message", clienttranslate('${player_name} will learn another level 1 trick after all players select their magicians and inital tricks'), [
                "player_id" => $activePlayerId
            ]);
        } else if ($specialistType === Character::TYPE_MANAGER) {
            Engine::insertAsChild([
                "state" => PickComponents::class,
                "args" => [
                    "location" => Components::LOCATION_MANAGER_BOARD
                ]
            ]);
        }
        
        return $this->resolve();
    }    
}