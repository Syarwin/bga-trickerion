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

class SetDie extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_SET_DIE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must set a die'),
            descriptionMyTurn: clienttranslate('${you} must set a die'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must set a die (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must set a die (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Set die (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Set die');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $sourceName = $this->getNodeArgs("sourceName");

        $availableDice = Globals::getDice();
        
        $args = [
            "sourceName" => $sourceName,
            "availableDice" => $availableDice,
            "availableFaces" => [
                Dice::DICE_TYPE_CHARACTER => [
                    0 => Dice::getCharacterDieFaces(0),
                    1 => Dice::getCharacterDieFaces(1)
                ],
                Dice::DICE_TYPE_TRICK => [
                    0 => Dice::getTrickDieFaces(),
                    1 => Dice::getTrickDieFaces()
                ],
                Dice::DICE_TYPE_MONEY => [
                    0 => Dice::getMoneyDieFaces(),
                    1 => Dice::getMoneyDieFaces()
                ]
            ]
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actSetDie(int $activePlayerId, string $dieType, int $dieId, string $dieFace)
    {
        Log::step();

        $availableDice = $this->getActionArgs($activePlayerId)["availableDice"];
        $availableFaces = $this->getActionArgs($activePlayerId)["availableFaces"];
        if (!array_key_exists($dieId, $availableDice[$dieType]) || !in_array($dieFace, $availableFaces[$dieType][$dieId])) {
            throw new UserException("You cannot set this die to this face");
        }

        Dice::setDie($dieType, $dieId, $dieFace);
        
        return $this->resolve(["dieType" => $dieType, "dieId" => $dieId, "dieFace" => $dieFace]);
    }
}