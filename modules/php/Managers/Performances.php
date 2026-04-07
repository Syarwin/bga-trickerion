<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Game;
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
            "active" => self::getInLocation(self::LOCATION_ACTIVE)->toArray(),
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
        self::create($performances, self::LOCATION_BOX, 0);

        self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_RIVERSIDE), self::LOCATION_DECK);
        self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_GRAND_MAGORIAN), self::LOCATION_DECK);

        if (Globals::isDarkAlley()) {
            self::insertAtBottom(self::getRandomIdsForTheater(Performance::THEATER_MAGNUS_PANTHEON), self::LOCATION_DECK);
        }

        $nrPlayers = Players::count();
        self::insertOnTop(self::getRandomIdsForTheater(Performance::THEATER_RIVERSIDE, $nrPlayers - 1), self::LOCATION_ACTIVE);
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

    public static function getMaxNumberOfPerformances() {
        return Players::count() + 1;
    }

    public static function getActive() {
        return self::getInLocation(self::LOCATION_ACTIVE);
    }

    public static function getTrickSetupData($playerId)
    {
        return self::getActive()
            ->map(function(Performance $performance) use ($playerId) {
                $possibleTricks = Tricks::getPrepared($playerId)->filter(function($trick) use ($performance) {
                    return $performance->canAddTrick($trick);
                });

                $availableSlots = $performance->getAvailableSlots();
                return [
                    "possibleTricks" => $possibleTricks->toArray(),
                    "possibleSlots" => $availableSlots
                ];
            });
    }

    public static function roundMaintenenace() {
        $topAvailablePerformance = Performances::getTopOf(self::LOCATION_ACTIVE)->first();

        if ($topAvailablePerformance->getState() == self::getMaxNumberOfPerformances()) {
            $trickMarkers = TrickMarkers::getOnPerformance($topAvailablePerformance->getId());
            TrickMarkers::returnToSupplies($trickMarkers);
            $topAvailablePerformance->setLocation(self::LOCATION_BOX);
            $topAvailablePerformance->setState(0);

            Game::get()->bga->notify->all("message", clienttranslate('${performance} is removed from the game and all trick markers still on it were returned to player supplies'), [
                "performance" => $topAvailablePerformance
            ]);
        }
        
        $activePerformances = Performances::getInLocation(self::LOCATION_ACTIVE)->forEach(function($performance) {
            $performance->incState();
        });
        Game::get()->bga->notify->all("performancesRotated", clienttranslate('Performances are moved clockwise'), [
            "performances" => $activePerformances
        ]);

        $newPerformance = Performances::pickOneForLocation(self::LOCATION_DECK, self::LOCATION_ACTIVE, 1);
        Game::get()->bga->notify->all("message", clienttranslate('New performance is revealed: ${performance}'), [
            "performance" => $newPerformance
        ]);
    }

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
