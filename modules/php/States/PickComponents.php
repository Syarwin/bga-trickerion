<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Player;
use Bga\Games\trickerionlegendsofillusion\States\Constants\States;

class PickComponents extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_PICK_COMPONENTS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must pick components to put in ${location} with a total value of ${totalValue} (remaining: ${remainingValue})'),
            descriptionMyTurn: clienttranslate('${you} must pick components to put in ${location} with a total value of ${totalValue} (remaining: ${remainingValue})'),
        );
    }

    public function getDescription() {
        return clienttranslate("Pick components");
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $pickingComponents = Globals::getPickingComponents();
        $totalPickedValue = 0;
        foreach ($pickingComponents as $component) {
            $totalPickedValue += Component::getCost($component);
        }

        $totalValue = $this->getNodeArgs("totalValue", 2);
        $location = $this->getNodeArgs("location", Components::LOCATION_PLAYER_BOARD);
        $args = [
            "totalValue" => $totalValue,
            "remainingValue" => $totalValue - $totalPickedValue,
            "availableComponents" => Components::getAffordableComponents($totalValue - $totalPickedValue),
            "location" => Components::getLocationName($location)
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actPickComponent(int $activePlayerId, string $component)
    {
        Log::step();
        $pickingComponents = Globals::getPickingComponents();
        
        $availableComponents = $this->getActionArgs($activePlayerId)["availableComponents"];

        if (!in_array($component, $availableComponents, true)) {
            throw new UserException(clienttranslate("You cannot pick this component because it exceeds the remaining value."));
        }

        $pickingComponents[] = $component;
        Globals::setPickingComponents($pickingComponents);

        return Engine::proceed();
    }

    #[PossibleAction]
    public function actDone(int $activePlayerId)
    {
        Log::step();
        $pickingComponents = Globals::getPickingComponents();

        /** @var Player $player */
        $player = Players::get($activePlayerId);

        $location = $this->getNodeArgs("location", Components::LOCATION_PLAYER_BOARD);

        foreach ($pickingComponents as $component) {
            $player->incComponent($component, 1, $location);
        }
        Globals::setPickingComponents([]);
        
        return Engine::proceed();
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