<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;

class SetupTrick extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_SETUP_TRICK,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must setup a trick'),
            descriptionMyTurn: clienttranslate('${you} must setup a trick'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must setup a trick (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must setup a trick (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Setup a trick (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Setup a trick');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availablePerformances = Performances::getPerformancesToSetupTrick($activePlayerId);
        $args = [
            "availablePerformances" => $availablePerformances,
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
    public function actSetupTrick(int $activePlayerId, int $trickId, int $performanceId, string $slotId, string $direction, array $args)
    {
        Log::step();
        /* @var Trick $trick */
        $trick = Tricks::get($trickId);
        
        //find availablePerformance from $args["availablePerformances"] that match performanceId
        $availablePerformance = null;
        foreach ($args["availablePerformances"] as $ap) {
            if ($ap["performance"]->getId() === $performanceId) {
                $availablePerformance = $ap;
                break;
            }
        }

        if (is_null($availablePerformance)) {
            throw new UserException(clienttranslate("You cannot choose this performance."));
        }

        if (!in_array($trick, $availablePerformance["possibleTricks"], true)) {
            throw new UserException(clienttranslate("You cannot choose this trick."));
        }

        if (!array_key_exists($slotId, $availablePerformance["possibleSlots"])) {
            throw new UserException(clienttranslate("You cannot choose this slot."));
        }

        $performance = $availablePerformance["performance"];

        if (!in_array($direction, $performance->getSlotDirections($slotId), true)) {
            throw new UserException(clienttranslate("You cannot choose this direction."));
        }

        $trick->setup($performance, $slotId, $direction);

        return $this->resolve(["trickId" => $trickId, "performanceId" => $performanceId, "slotId" => $slotId, "direction" => $direction]);
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