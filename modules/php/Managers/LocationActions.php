<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\States\Actions\DrawAssignmentCards;
use Bga\Games\trickerionlegendsofillusion\States\Actions\EnhanceCharacter;
use Bga\Games\trickerionlegendsofillusion\States\Actions\HireCharacter;
use Bga\Games\trickerionlegendsofillusion\States\Actions\LearnTrick;

class LocationActions
{
    public static function init($locationId = null, $actionPoints = 0) {
        Globals::setLocationActions([
            "locationId" => $locationId,
            "remainingActionPoints" => $actionPoints,
            "oneTimeActionsUsed" => []
        ]);
    }

    public static function incActionPoints(int $points) {
        $locationActions = Globals::getLocationActions();
        $locationActions["remainingActionPoints"] += $points;
        Globals::setLocationActions($locationActions);
    }

    public static function getRemainingActionPoints() {
        $locationActions = Globals::getLocationActions();
        return $locationActions["remainingActionPoints"];
    }

    public static function isOneTimeActionUsed($actionId) {
        $locationActions = Globals::getLocationActions();
        return in_array($actionId, $locationActions["oneTimeActionsUsed"]);
    }

    public static function markOneTimeActionUsed($actionId) {
        $locationActions = Globals::getLocationActions();
        if (!in_array($actionId, $locationActions["oneTimeActionsUsed"])) {
            $locationActions["oneTimeActionsUsed"][] = $actionId;
            Globals::setLocationActions($locationActions);
        }
    }

    public static function getActions($playerId) {
        $availableActions = self::getAvailableLocationActions();
        
        //filter out one time actions that have already been used
        foreach ($availableActions as $actionKey => $action) {
            if (($action["singleUse"] ?? false) && self::isOneTimeActionUsed($actionKey)) {
                unset($availableActions[$actionKey]);
            }

            if (isset($action["ifCharacterHired"])) {
                $characterType = $action["ifCharacterHired"];
                if (!Characters::isCharacterHired($playerId, $characterType)) {
                    unset($availableActions[$actionKey]);
                }
            }

            $actionPointsNeeded = $action["minActionPoints"] ?? $action["actionPoints"];

            if ($actionPointsNeeded !== null && $actionPointsNeeded > self::getRemainingActionPoints()) {
                unset($availableActions[$actionKey]);
            }

            $shardCost = $action["shardCost"] ?? 0;
            if ($shardCost > 0 && Players::get($playerId)->getShards() < $shardCost) {
                unset($availableActions[$actionKey]);
            }
        }

        return $availableActions;
    }

    public static function playAction($playerId, $actionId) {
        $availableActions = self::getActions($playerId);
        if (!isset($availableActions[$actionId])) {
            throw new UserException("Action not available: " . $actionId);
        }

        $selectedAction = $availableActions[$actionId];

        $actionPoints = $selectedAction["actionPoints"] ?? 0;
        self::incActionPoints(-$actionPoints);
        
        if (($selectedAction["singleUse"] ?? false)) {
            self::markOneTimeActionUsed($actionId);
        }

        Engine::insertAsChild([
            "state" => $selectedAction["state"],
            "args" => $selectedAction["args"] ?? []
        ]);
    }

    private static function getAvailableLocationActions() {
        $locationActions = Globals::getLocationActions();
        return match ($locationActions["locationId"]) {
            Characters::LOCATION_BOARD_DARK_ALLEY_1,
            Characters::LOCATION_BOARD_DARK_ALLEY_2,
            Characters::LOCATION_BOARD_DARK_ALLEY_3,
            Characters::LOCATION_BOARD_DARK_ALLEY_4 => [
                "draw_Assignment_cards" => [
                    "state" => DrawAssignmentCards::class,
                    //during action AP will be spent so minActionPoints indicates whether the action can be selected but will not spend AP immediately
                    "minActionPoints" => DrawAssignmentCardsAction::getCurrentCost(),
                    "singleUse" => false
                ],
                //draw_further_cards would be a part of draw_first_card (DrawAssignmentCards) action,
                "fortune_telling" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => true
                ],
                "enhance_character" => [
                    "state" => EnhanceCharacter::class,
                    "actionPoints" => 0,
                    "shardCost" => 1,
                    "singleUse" => true
                ]
            ],

            Characters::LOCATION_BOARD_DOWNTOWN_1,
            Characters::LOCATION_BOARD_DOWNTOWN_2,
            Characters::LOCATION_BOARD_DOWNTOWN_3,
            Characters::LOCATION_BOARD_DOWNTOWN_4 => [
                "learn_trick" => [
                    "state" => LearnTrick::class,
                    "actionPoints" => 3,
                    "singleUse" => false
                ],
                "hire_character" => [
                    "state" => HireCharacter::class,
                    "actionPoints" => 3,
                    "singleUse" => false
                ],
                "take_coins" => [
                    "state" => null,
                    "actionPoints" => 3,
                    "singleUse" => false
                ],
                "reroll_die" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false
                ],
                "set_die" => [
                    "state" => null,
                    "actionPoints" => 2,
                    "singleUse" => false
                ],
                "enhance_character" => [
                    "state" => EnhanceCharacter::class,
                    "actionPoints" => 0,
                    "singleUse" => true,
                    "shardCost" => 1
                ]
            ],

            Characters::LOCATION_BOARD_MARKET_ROW_1,
            Characters::LOCATION_BOARD_MARKET_ROW_2,
            Characters::LOCATION_BOARD_MARKET_ROW_3,
            Characters::LOCATION_BOARD_MARKET_ROW_4 => [
                "buy" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false
                ],
                //bargain is part of buy action and will be handled in that state
                "order" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false
                ],
                "quick_order" => [
                    "state" => null,
                    "actionPoints" => 2,
                    "singleUse" => false
                ],
                "enhance_character" => [
                    "state" => EnhanceCharacter::class,
                    "actionPoints" => 0,
                    "singleUse" => true,
                    "shardCost" => 1
                ]
            ],

            Characters::LOCATION_BOARD_WORKSHOP_1,
            Characters::LOCATION_BOARD_WORKSHOP_2 => [
                "prepare" => [
                    "state" => null,
                    "actionPoints" => 0,
                    "singleUse" => false
                ],
                "move_tricks" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false,
                    "ifCharacterHired" => Character::TYPE_ENGINEER
                ],
                "move_components" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false,
                    "ifCharacterHired" => Character::TYPE_MANAGER
                ],
                "move_apprentice" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false,
                    "ifCharacterHired" => Character::TYPE_ASSISTANT
                ],
                "enhance_character" => [
                    "state" => EnhanceCharacter::class,
                    "actionPoints" => 0,
                    "singleUse" => true,
                    "shardCost" => 1
                ]
            ],

            Characters::LOCATION_BOARD_THEATER_THURSDAY_BASIC_1,
            Characters::LOCATION_BOARD_THEATER_THURSDAY_BASIC_2,
            Characters::LOCATION_BOARD_THEATER_FRIDAY_BASIC_1,
            Characters::LOCATION_BOARD_THEATER_FRIDAY_BASIC_2,
            Characters::LOCATION_BOARD_THEATER_SATURDAY_BASIC_1,
            Characters::LOCATION_BOARD_THEATER_SATURDAY_BASIC_2,
            Characters::LOCATION_BOARD_THEATER_SUNDAY_BASIC_1,
            Characters::LOCATION_BOARD_THEATER_SUNDAY_BASIC_2 => [
                "set_up_trick" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false
                ],
                "reschedule" => [
                    "state" => null,
                    "actionPoints" => 1,
                    "singleUse" => false
                ]
            ],

            Characters::LOCATION_BOARD_THEATER_THURSDAY_MAGICIAN,
            Characters::LOCATION_BOARD_THEATER_FRIDAY_MAGICIAN,
            Characters::LOCATION_BOARD_THEATER_SATURDAY_MAGICIAN,
            Characters::LOCATION_BOARD_THEATER_SUNDAY_MAGICIAN => [
                "magician_ready_to_perform" => [
                    "state" => null,
                    "actionPoints" => null,
                    "singleUse" => false
                ]
            ],

            default => throw new \InvalidArgumentException("Unknown location: " . $locationActions["locationId"]),
        };
    }
}