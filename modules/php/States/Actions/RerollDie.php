<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Managers\Dice;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;

class RerollDie extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_REROLL_DIE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must reroll a die'),
            descriptionMyTurn: clienttranslate('${you} must reroll a die'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must reroll a die (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must reroll a die (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Reroll die (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Reroll die');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $sourceName = $this->getNodeArgs("sourceName");

        $availableDice = Globals::getDice();
        
        $args = [
            "sourceName" => $sourceName,
            "availableDice" => $availableDice
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actRerollDie(int $activePlayerId, string $dieType, int $dieId)
    {
        Log::step();

        $availableDice = $this->getActionArgs($activePlayerId)["availableDice"];
        if (!array_key_exists($dieType, $availableDice) || !array_key_exists($dieId, $availableDice[$dieType])) {
            throw new UserException("You cannot reroll this die");
        }

        Dice::rerollDie($dieType, $dieId);
        
        return $this->resolveIrreversible(["dieType" => $dieType, "dieId" => $dieId]);
    }
}