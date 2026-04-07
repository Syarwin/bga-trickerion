<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Posters;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;

class Advertise extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct(
            $game,
            node: $node,
            id: States::ST_ADVERTISE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must decide whether to advertise for ${cost} coins'),
            descriptionMyTurn: clienttranslate('${you} must decide whether to advertise for ${cost} coins'),
        );
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "cost" => Players::get($activePlayerId)->getInitiative(),
        ];
        return $args;
    }

    public function isOptional()
    {
        return true;
    }

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actAdvertise(int $activePlayerId, array $args)
    {
        Log::step();
        $fame = 2;

        $player = Players::get($activePlayerId);
        $player->payCoins($args['cost']);
        $player->addFame($fame);

        $poster = Posters::getFiltered($activePlayerId, Posters::LOCATION_SUPPLY)
            ->first()
            ->setLocation(Posters::LOCATION_BOARD);

        $this->notify->all("advertised", clienttranslate('${player_name} advertises and places their poster on the board for ${cost} coins and receives <fame> fame'), [
            "player_id" => $activePlayerId,
            "poster" => $poster,
            "cost" => $args['cost'],
            "fame" => $fame
        ]);

        return $this->resolve(["advertise" => true]);
    }
}
