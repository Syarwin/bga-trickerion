<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Models\Player;
use Bga\Games\trickerionlegendsofillusion\Game;

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
    }

}
