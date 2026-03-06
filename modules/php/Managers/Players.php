<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

/*
 * Players manager : allows to easily access players ...
 *  a player is an instance of Player class
 */
class Players extends \Bga\Games\trickerionlegendsofillusion\Framework\Managers\Players
{
    protected static function cast($row)
    {
        return new \Bga\Games\trickerionlegendsofillusion\Models\Player($row);
    }

    public static function setupNewGame($players)
    {
        parent::setupNewGame($players);
        // do custom setup by using self::getAll() or similar
        $coins = 10;
        $initiative = 1;
        foreach (self::getTurnOrder() as $pId) {
            $player = self::get($pId);
            $player->incCoins($coins);
            $coins += 2;

            $player->setInitiative($initiative);
            $initiative += 1;

            $player->setColorName(self::getColorName($player->getColor()));
            $player->setShards(1);
            $player->setScore(5);
        }
    }

    private static function getColorName($color) {
        return $colorNames = [
            "60aaa1" => "blue",
            "cf4a1f" => "red",
            "cc7f17" => "orange",
            "85902b" => "green",
        ][$color] ?? "unknown";
    }

}
