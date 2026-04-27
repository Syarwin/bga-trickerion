<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;

class Performance extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PERFORMANCE,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose a performance to perform for ${day}'),
            descriptionMyTurn: clienttranslate('${you} must choose a performance to perform for ${day}'),
        );
    }

    public function onEnteringState() {
        $skip = $this->getNodeArgs("skip") ?? false;
        if ($skip) {
            $this->bga->notify->all("message", clienttranslate('No player is performing for ${day}, skipping'), [
                "day" => $this->getNodeArgs("day"),
            ]);

            return $this->resolve(["skipped" => true, "automatic" => true]);
        }
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $args = [
            "day" => $this->getNodeArgs("day"),
            "availablePerformances" => Performances::getInLocation(Performances::LOCATION_ACTIVE)->toArray()
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actSelectPerformance(int $activePlayerId, int $performanceId, array $args)
    {
        Log::step();
        $performance = Performances::get($performanceId);
        if (!in_array($performance, $args["availablePerformances"])) {
            throw new UserException(clienttranslate("This performance is not available"));
        }
        $this->bga->notify->all("performanceChosen", clienttranslate('${player_name} chooses performance ${performance}'), [
            "player_id" => $activePlayerId,
            "performance" => $performance,
        ]);

        $performance->perform($activePlayerId);

        return $this->resolve(["performanceId" => $performanceId]);
    }    
}