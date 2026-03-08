<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class Dice
{
    public static function init() {
        Globals::setDice([
            "trick" => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ],
            "character" => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ],
            "money" => [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
            ]
        ]);
    }

    public static function roll() {
        $dice = Globals::getDice();
        $dice["trick"] = [
            self::getRandomTrickDie(),
            self::getRandomTrickDie(),
        ];
        $dice["character"] = [
            self::getRandomCharacterDie(1),
            self::getRandomCharacterDie(2),
        ];
        $dice["money"] = [
            self::getRandomMoneyDie(),
            self::getRandomMoneyDie(),
        ];
        Globals::setDice($dice);

        Game::get()->bga->notify->all("rollDice", clienttranslate('Dice rolled: ${dice}'), [
            "dice" => $dice
        ]);
    }

    private static function getRandomTrickDie() {
        $i = bga_rand(0, 5);
        return [
            Trick::CATEGORY_ESCAPE,
            Trick::CATEGORY_MECHANICAL,
            Trick::CATEGORY_OPTICAL,
            Trick::CATEGORY_SPIRITUAL,
            self::ANY,
            self::NOT_AVAILABLE
        ][$i];
    }

    private static function getRandomCharacterDie(int $die) {
        $i = bga_rand(0, 5);
        if ($die == 1) {
            return [
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
                self::NOT_AVAILABLE,
                Character::TYPE_ASSISTANT,
                Character::TYPE_MANAGER,
                Character::TYPE_ASSISTANT
            ][$i];
        } else {
            return [
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                Character::TYPE_APPRENTICE,
                self::NOT_AVAILABLE
            ][$i];
        }
    }

    private static function getRandomMoneyDie() {
        $i = bga_rand(0, 5);
        return [
            3,
            4,
            4,
            5,
            6,
            self::NOT_AVAILABLE
        ][$i];
    }

    const ANY = "any";
    const NOT_AVAILABLE = "not-available";
}