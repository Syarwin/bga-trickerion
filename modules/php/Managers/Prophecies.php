<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;

class Prophecies extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'prophecy';
    protected static $prefix = 'prophecy_';
    protected static $customFields = ["prophecy_type"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return self::getProphecyInstance($raw["prophecy_type"], $raw);
    }

    public static function getProphecyInstance($type, $data = null)
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Prophecies\\$type";
        return new $className($data);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "pending" => self::getInLocation(self::LOCATION_PENDING)->toArray(),
            "active" => self::getInLocation(self::LOCATION_ACTIVE)->toArray(),
            "deckRemaining" => self::getInLocation(self::LOCATION_DECK)->count(),
            "discarded" => self::getInLocationOrdered(self::LOCATION_DISCARD)->toArray(),
        ];
    }

    /*
  ███████╗███████╗████████╗██╗   ██╗██████╗
  ██╔════╝██╔════╝╚══██╔══╝██║   ██║██╔══██╗
  ███████╗█████╗     ██║   ██║   ██║██████╔╝
  ╚════██║██╔══╝     ██║   ██║   ██║██╔═══╝
  ███████║███████╗   ██║   ╚██████╔╝██║
  ╚══════╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝
  */

    /* Creation of the cards */
    public static function setupNewGame()
    {
        if (!Globals::isIncludeProphecies()) {
            return;
        }

        // Load list of cards
        include dirname(__FILE__) . '/../Prophecies/list.php';

        // Create cards
        $prophecies = [];
        foreach ($prophecyTypes as $type) {
            $data = [
                'prophecy_type' => $type,
                'nbr' => 1,
            ];

            $prophecies[] = $data;
        }

        // Create the tricks
        self::create($prophecies, self::LOCATION_DECK);
        self::shuffle(self::LOCATION_DECK);

        // Pending prophecies are revealed after the players pick magicians, tricks, components and characters
        // see FinishSetup class
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */


    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */

    const LOCATION_DECK = 'deck';
    const LOCATION_ACTIVE = 'active';
    const LOCATION_PENDING = 'pending';
    const LOCATION_DISCARD = 'discard';
}
