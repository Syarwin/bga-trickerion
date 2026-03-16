<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\GameFramework\NotificationMessage;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Game;

class DrawAssignmentCardsAction
{
    public static function init() {
        Globals::setDrawAssignmentCardsAction([
            "currentCost" => 1,
        ]);
    }

    public static function getDrawnCards() : array {
        return Assignments::getInLocation(Assignments::LOCATION_DRAWN)->toArray();
    }

    public static function drawCards(int $playerId, string $deckLocationId) {
        $currentCost = self::getCurrentCost();
        $remainingActionPoints = LocationActions::getRemainingActionPoints();

        if ($currentCost > $remainingActionPoints) {
            throw new UserException(clienttranslate("Not enough action points to draw assignment cards"));
        }

        $newCards = Assignments::pickForLocation(2, $deckLocationId, Assignments::LOCATION_DRAWN);
        $newCards->update("playerId", $playerId);
        LocationActions::incActionPoints(-$currentCost);
        self::setAdditionalDrawCost();

        Game::get()->notify->all("assignmentsDrawn", clienttranslate('${player_name} draws ${count} assignment cards'), [
            "player_id" => $playerId,
            "count" => count($newCards),
            '_private' => [
                $playerId => new NotificationMessage(clienttranslate('You draw ${_private.assignments}'), [
                    "assignments" => $newCards->toArray()
                ]),
             ],
        ]);
        
        return $newCards;
    }

    public static function discardCards(int $playerId, array $cardIds) {
        $allDrawnAssignments = Assignments::getFiltered($playerId, Assignments::LOCATION_DRAWN);
        
        $toDiscardPerBoardLocation = $allDrawnAssignments->group("boardLocation")->map(function($group) {
            return count($group) / 2;
        })->toAssoc();

        $discardedCards = Assignments::getMany($cardIds);
        $discardedCards->forEach(function($card) use ($playerId) {
            if ($card->getPlayerId() !== $playerId || $card->getLocation() !== Assignments::LOCATION_DRAWN) {
                throw new UserException(clienttranslate("You can only discard cards that you just drew"));
            }
        });

        $discardedCards->group("boardLocation")->forEach(function($group, $boardLocation) use ($toDiscardPerBoardLocation) {
            $toDiscard = $toDiscardPerBoardLocation[$boardLocation] ?? 0;
            if (count($group) !== $toDiscard) {
                throw new UserException(new NotificationMessage(clienttranslate('You must discard exactly ${n} cards from ${boardLocation}'), [
                    "n" => $toDiscard,
                    "boardLocation" => $boardLocation
                ]));
            }
        });

        if ($discardedCards->count() !== $allDrawnAssignments->count() / 2) {
            throw new UserException(clienttranslate('You must discard exactly half of the drawn cards'));
        }

        $discardedCards->forEach(function($card) {
            Assignments::insertAtBottom($card->getId(), $card->getBoardLocation());
        });

        $keptAssignments = Assignments::moveAllInLocation(Assignments::LOCATION_DRAWN, Assignments::LOCATION_HAND);

        Game::get()->notify->all("assignmentsDiscarded", clienttranslate('${player_name} discarded ${count} of the drawn assignment cards'), [
            "player_id" => $playerId,
            "count" => $discardedCards->count(),
            '_private' => [
                $playerId => new NotificationMessage(clienttranslate('You discarded ${_private.assignments}'), [
                    "assignments" => $discardedCards->toArray(),
                    "keptAssignments" => $keptAssignments->toArray()
                ]),
             ],
        ]);
    }

    public static function getCurrentCost() : int {
        return Globals::getDrawAssignmentCardsAction()["currentCost"] ?? 0;
    }

    public static function setAdditionalDrawCost() {
        $drawAssignmentCardsAction = Globals::getDrawAssignmentCardsAction();
        $drawAssignmentCardsAction["currentCost"] = 2;
        Globals::setDrawAssignmentCardsAction($drawAssignmentCardsAction);
    }
}