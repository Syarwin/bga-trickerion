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
        $args = [
            "availablePerformances" => Performances::getActive()->toArray(),
            "possibleTricksAndSlots" => Performances::getTrickSetupData($activePlayerId)->toAssoc(),
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

        $performance = Performances::get($performanceId);
        
        if (!in_array($performance, $args["availablePerformances"], true)) {
            throw new UserException(clienttranslate("You cannot choose this performance."));
        }

        if (!in_array($trick, $args["possibleTricksAndSlots"][$performanceId]["possibleTricks"], true)) {
            throw new UserException(clienttranslate("You cannot choose this trick."));
        }

        if (!array_key_exists($slotId, $args["possibleTricksAndSlots"][$performanceId]["possibleSlots"])) {
            throw new UserException(clienttranslate("You cannot choose this slot."));
        }

        if (!in_array($direction, $performance->getSlotDirections($slotId), true)) {
            throw new UserException(clienttranslate("You cannot choose this direction."));
        }

        $trick->setup($performance, $slotId, $direction);

        return $this->resolve(["trickId" => $trickId, "performanceId" => $performanceId, "slotId" => $slotId, "direction" => $direction]);
    }
}