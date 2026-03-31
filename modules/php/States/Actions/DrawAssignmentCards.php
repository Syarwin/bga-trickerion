<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\DrawAssignmentCardsAction;
use Bga\Games\trickerionlegendsofillusion\Managers\LocationActions;

class DrawAssignmentCards extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_DRAW_ASSIGNMENT_CARDS,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must draw assignment cards'),
            descriptionMyTurn: clienttranslate('${you} must draw assignment cards'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must draw assignment cards (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must draw assignment cards (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Draw assignment cards (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Draw assignment cards');
    }

    public function isOptional() {
        return count(DrawAssignmentCardsAction::getDrawnCards()) == 0;
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $drawnCards = DrawAssignmentCardsAction::getDrawnCards();
        $remainingActionPoints = LocationActions::getRemainingActionPoints();
        $cost = DrawAssignmentCardsAction::getCurrentCost();
        $args = [
            "drawnCards" => $drawnCards,
            "currentDrawCost" => $cost,
            "canDraw" => $cost <= $remainingActionPoints,
            "availableLocations" => [
                Assignments::LOCATION_THEATER_DECK,
                Assignments::LOCATION_WORKSHOP_DECK,
                Assignments::LOCATION_MARKET_ROW_DECK,
                Assignments::LOCATION_DOWNTOWN_DECK,
            ]
        ];
        return $args;
    }

    #[PossibleAction]
    public function actDrawAssignmentCards(int $activePlayerId, string $deckLocationId)
    {
        Log::step();
        DrawAssignmentCardsAction::drawCards($activePlayerId, $deckLocationId);
        Log::checkpoint();
        return Engine::proceed();
    }
    
    #[PossibleAction]
    public function actDiscardCards(int $activePlayerId, #[IntArrayParam()] array $cardIds)
    {
        Log::step();
        DrawAssignmentCardsAction::discardCards($activePlayerId, $cardIds);
        return $this->resolve([]);
    }
}