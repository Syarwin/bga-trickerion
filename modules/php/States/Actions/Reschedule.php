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
use Bga\Games\trickerionlegendsofillusion\Managers\TrickMarkers;
use Bga\Games\trickerionlegendsofillusion\Models\TrickMarker;

class Reschedule extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_RESCHEDULE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must reschedule a trick'),
            descriptionMyTurn: clienttranslate('${you} must reschedule a trick'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must reschedule a trick (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must reschedule a trick (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Reschedule a trick (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Reschedule a trick');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableTricks = TrickMarkers::getScheduled($activePlayerId);
        $args = [
            "availableTrickMarkers" => $availableTricks->toArray(),
            "possiblePerformances" => TrickMarkers::getRescheduleData($activePlayerId)->toAssoc(),
            "sourceName" => $this->getNodeArgs("sourceName", "")
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actRescheduleTrick(int $activePlayerId, int $trickMarkerId, int $performanceId, string $slotId, string $direction, array $args)
    {
        Log::step();

        /* @var TrickMarker $trickMarker */
        $trickMarker = TrickMarkers::get($trickMarkerId);
        
        if (!in_array($trickMarker, $args["availableTrickMarkers"], true)) {
            throw new UserException(clienttranslate("You cannot choose this trick marker."));
        }
        
        if (!array_key_exists($performanceId, $args["possiblePerformances"][$trickMarkerId])) {
            throw new UserException(clienttranslate("You cannot choose this performance."));
        }

        if (!array_key_exists($slotId, $args["possiblePerformances"][$trickMarkerId][$performanceId]["possibleSlots"])) {
            throw new UserException(clienttranslate("You cannot choose this slot."));
        }
        
        if (!in_array($direction, $args["possiblePerformances"][$trickMarkerId][$performanceId]["performance"]->getSlotDirections($slotId), true)) {
            throw new UserException(clienttranslate("You cannot choose this direction."));
        }

        $trickMarker->moveToPerformance($performanceId, $slotId, $direction);

        return $this->resolve(["trickMarkerId" => $trickMarkerId, "performanceId" => $performanceId, "slotId" => $slotId, "direction" => $direction]);
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