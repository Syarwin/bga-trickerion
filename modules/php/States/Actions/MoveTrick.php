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
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

class MoveTrick extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_MOVE_TRICK,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide which trick to move'),
            descriptionMyTurn: clienttranslate('${you} must decide which trick to move'),
        );
    }

    public function getDescription() {
        return clienttranslate('Move trick');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "availableTricks" => Tricks::getFiltered($activePlayerId, Tricks::LOCATION_PLAYER_BOARD)->toArray(),
            "engineerBoardTrick" => Tricks::getFiltered($activePlayerId, Tricks::LOCATION_ENGINEER_BOARD)->first()
        ];
        return $args;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actMoveTrick(int $activePlayerId, array $args, int $trickId)
    {
        Log::step();

        $trick = Tricks::get($trickId);

        if (!in_array($trick, $args["availableTricks"])) {
            throw new UserException(clienttranslate("You cannot move this trick"));
        }

        $trick->move();

        return $this->resolve(["trickId" => $trickId,]);
    } 
}