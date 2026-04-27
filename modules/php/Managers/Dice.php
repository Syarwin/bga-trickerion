<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class Dice
{
    public static function init() {
        Globals::setDice([
            self::DICE_TYPE_TRICK => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ],
            self::DICE_TYPE_CHARACTER => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ],
            self::DICE_TYPE_MONEY => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ]
        ]);
    }

    public static function roll() {
        $dice = Globals::getDice();
        $dice[self::DICE_TYPE_TRICK] = [
            self::getRandomTrickDie(),
            self::getRandomTrickDie(),
        ];
        $dice[self::DICE_TYPE_CHARACTER] = [
            self::getRandomCharacterDie(0),
            self::getRandomCharacterDie(1),
        ];
        $dice[self::DICE_TYPE_MONEY] = [
            self::getRandomMoneyDie(),
            self::getRandomMoneyDie(),
        ];
        Globals::setDice($dice);

        Game::get()->bga->notify->all("diceRolled", clienttranslate('Dice rolled: ${dice}'), [
            "dice" => $dice
        ]);
    }

    public static function setDieUnavailable(string $dieType, string|int $dieFace) {
        $dice = Globals::getDice();
        if (isset($dice[$dieType])) {
            $dieIndex = array_search($dieFace, $dice[$dieType]);
            if ($dieIndex !== false) {
                $dice[$dieType][$dieIndex] = self::NOT_AVAILABLE;
                Globals::setDice($dice);

                Game::get()->bga->notify->all("dieMadeUnavailable", clienttranslate('${player_name} has turned ${dieFace} to "X"'), [
                    "player_id" => Players::getActiveId(),
                    "dieFace" => $dieFace,
                    "dieId" => $dieIndex,
                ]);
            }
        }
    }

    public static function getDice(string $dieType, bool $onlyAvailable = true) {
        $dice = Globals::getDice();
        $typeDice = $dice[$dieType] ?? [self::NOT_AVAILABLE, self::NOT_AVAILABLE];
        if ($onlyAvailable) {
            return array_values(array_filter($typeDice, function($die) {
                return $die !== self::NOT_AVAILABLE;
            }));
        }
        return $typeDice;
    }

    public static function rerollDie(string $dieType, int $dieId) {
        $dice = Globals::getDice();
        if (isset($dice[$dieType])) {
            if (isset($dice[$dieType][$dieId])) {
                $dieFace = $dice[$dieType][$dieId];
                // Reroll the die
                switch ($dieType) {
                    case self::DICE_TYPE_TRICK:
                        $dice[$dieType][$dieId] = self::getRandomTrickDie();
                        break;
                    case self::DICE_TYPE_CHARACTER:
                        $dice[$dieType][$dieId] = self::getRandomCharacterDie($dieId);
                        break;
                    case self::DICE_TYPE_MONEY:
                        $dice[$dieType][$dieId] = self::getRandomMoneyDie();
                        break;
                }
                Globals::setDice($dice);

                Game::get()->bga->notify->all("dieRerolled", clienttranslate('${player_name} has rerolled ${dieFace} to ${newDieFace}'), [
                    "player_id" => Players::getActiveId(),
                    "dieFace" => $dieFace,
                    "newDieFace" => $dice[$dieType][$dieId],
                    "newDice" => $dice
                ]);
            }
        }
    }

    public static function setDie(string $dieType, int $dieId, string|int $dieFace) {
        $dice = Globals::getDice();
        if (isset($dice[$dieType]) && isset($dice[$dieType][$dieId])) {
            $oldDieFace = $dice[$dieType][$dieId];
            $dice[$dieType][$dieId] = $dieFace;
            Globals::setDice($dice);

            Game::get()->bga->notify->all("dieSet", clienttranslate('${player_name} has set ${oldDieFace} to ${newDieFace}'), [
                "player_id" => Players::getActiveId(),
                "oldDieFace" => $oldDieFace,
                "newDieFace" => $dieFace,
                "newDice" => $dice
            ]);
        }
    }

    public static function getTrickDieFaces() {
        return [
            Trick::CATEGORY_ESCAPE,
            Trick::CATEGORY_MECHANICAL,
            Trick::CATEGORY_OPTICAL,
            Trick::CATEGORY_SPIRITUAL,
            self::ANY,
            self::NOT_AVAILABLE
        ];
    }

    private static function getRandomTrickDie() {
        $i = bga_rand(0, 5);
        return self::getTrickDieFaces()[$i];
    }

    public static function getCharacterDieFaces(int $die) {
        if ($die == 0) {
            return [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
                Character::TYPE_ASSISTANT,
                Character::TYPE_MANAGER,
                Character::TYPE_ENGINEER
            ];
        } else {
            return [
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                self::NOT_AVAILABLE
            ];
        }
    }

    private static function getRandomCharacterDie(int $die) {
        $i = bga_rand(0, 5);
        return self::getCharacterDieFaces($die)[$i];
    }

    public static function getMoneyDieFaces() {
        return [
            3,
            4,
            4,
            5,
            6,
            self::NOT_AVAILABLE
        ];
    }

    private static function getRandomMoneyDie() {
        $i = bga_rand(0, 5);
        return self::getMoneyDieFaces()[$i];
    }

    const ANY = "any";
    const NOT_AVAILABLE = "not-available";

    const DICE_TYPE_TRICK = "trick";
    const DICE_TYPE_CHARACTER = "character";
    const DICE_TYPE_MONEY = "money";
}