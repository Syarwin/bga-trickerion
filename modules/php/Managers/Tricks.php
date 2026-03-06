<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;

class Tricks extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'trick';
    protected static $prefix = 'trick_';
    protected static $customFields = ["trick_type", "player_id", "trick_suit"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return self::getTrickInstance($raw["trick_type"], $raw);
    }

    public static function getTrickInstance($type, $data = null)
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Tricks\\$type";
        return new $className($data);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "available" => self::getInLocation(self::LOCATION_AVAILABLE)->toArray(),
            "player" => Players::getAll()->map(function($player) {
                return self::getFiltered($player->id, self::LOCATION_PLAYER_ALL)->toArray();
            }),
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
        // Load list of cards
        include dirname(__FILE__) . '/../Tricks/list.php';

        // Create cards
        $tricks = [];
        foreach ($trickTypes as $type) {
            $trick = self::getTrickInstance($type);

            $location = self::LOCATION_AVAILABLE;

            if ($trick->getLevel() === 3 && !Globals::isDarkAlley()) {
                $location = self::LOCATION_BOX;
            }

            $data = [
                'trick_type' => $type,
                'location' => $location,
                'nbr' => 1,
            ];

            $tricks[] = $data;
        }

        // Create the tricks
        self::create($tricks, null);
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

    const LOCATION_AVAILABLE = 'available';
    const LOCATION_PLAYER_ALL = "%-board";
    const LOCATION_PLAYER_BOARD = 'player-board';
    const LOCATION_ENGINEER_BOARD = 'engineer-board';
    const LOCATION_BOX = 'box';
}
