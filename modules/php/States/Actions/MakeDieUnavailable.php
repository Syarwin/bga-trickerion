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
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;

class MakeDieUnavailable extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MAKE_DIE_UNAVAILABLE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose which die to turn to "X"'),
            descriptionMyTurn: clienttranslate('${you} must choose which die to turn to "X"'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must choose which die to turn to "X" (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must choose which die to turn to "X" (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Choose which die to turn to "X" (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Choose which die to turn to "X"');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $dice = Dice::getDice($this->getNodeArgs("dieType"));
        $args = [
            "availableDice" => $dice,
            "sourceName" => $this->getNodeArgs("sourceName")
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actMakeDieUnavailable(int $activePlayerId, string $dieFace, array $args)
    {
        Log::step();

        if (!in_array($dieFace, $args["availableDice"])) {
            throw new UserException(clienttranslate("This die is not available"));
        }

        Dice::setDieUnavailable($this->getNodeArgs("dieType"), $dieFace);
        
        return $this->resolve(["dieFace" => $dieFace]);
    }
}