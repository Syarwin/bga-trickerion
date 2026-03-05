<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class Performances extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'performance';
    protected static $prefix = 'performance_';
    protected static $customFields = ["performance_type"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return self::getPerformanceInstance($raw["performance_type"], $raw);
    }

    public static function getPerformanceInstance($type, $data = null)
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Performances\\$type";
        return new $className($data);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "active" => self::getInLocation(self::LOCATION_ACTIVE),
            "deck" => self::getInLocation(self::LOCATION_DECK)->map(function($performance) {
                return [
                    "state" => $performance->getState(),
                    "theater" => $performance->getTheater()
                ];
            })->toArray(),
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
        include dirname(__FILE__) . '/../Performances/list.php';

        // Create cards
        $performances = [];
        foreach ($performanceTypes as $type) {
            $data = [
                'performance_type' => $type,
                'nbr' => 1,
            ];

            $performances[] = $data;
        }

        // Create the tricks
        self::create($performances, self::LOCATION_BOX);

        self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_RIVERSIDE), self::LOCATION_DECK);
        self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_GRAND_MAGORIAN), self::LOCATION_DECK);

        if (Globals::isDarkAlley()) {
            self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_MAGNUS_PANTHEON), self::LOCATION_DECK);
        }

        $nrPlayers = Players::count();
        self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_RIVERSIDE, $nrPlayers - 1), self::LOCATION_ACTIVE);
    }

    private static function getRandomIdsForTheater($theater, $count = 2) {
        $theaterPerformanceIds = self::getInLocation(self::LOCATION_BOX)->where("theater", $theater)->getIds();
        shuffle($theaterPerformanceIds);
        return array_slice($theaterPerformanceIds, 0, $count);
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
    const LOCATION_BOX = 'box';
}
