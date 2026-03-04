<?php

namespace Bga\Games\Trickerion\Managers;

use Bga\Games\Trickerion\Models\Player;
use Bga\Games\Trickerion\Game;

/*
 * Players manager : allows to easily access players ...
 *  a player is an instance of Player class
 */
class Players extends \Bga\Games\Trickerion\Framework\Managers\Players
{
    protected static function cast($row)
    {
        return new \Bga\Games\Trickerion\Models\Player($row);
    }

    public static function setupNewGame($players)
    {
        parent::setupNewGame($players);
        // do custom setup by using self::getAll() or similar
    }

}
