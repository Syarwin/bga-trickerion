<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions\Anytime;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

class DiscardTrick extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_DISCARD_TRICK,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide which trick to discard'),
            descriptionMyTurn: clienttranslate('${you} must decide which trick to discard'),
        );
    }

    public function getDescription() {
        return clienttranslate('Discard trick');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "availableTricks" => Tricks::getFiltered($activePlayerId, Tricks::LOCATION_PLAYER_ALL)->toArray()
        ];
        return $args;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actDiscardTrick(int $activePlayerId, array $args, int $trickId)
    {
        Log::step();

        $availableIds = Collection::from($args["availableTricks"])->pluck("id")->toArray();
        if (!in_array($trickId, $availableIds)) {
            throw new UserException(clienttranslate("You cannot discard this trick"));
        }

        $trick = Tricks::get($trickId);
        $trick->discard();

        return $this->resolve(["discardTrickId" => $trickId]);
    }
}