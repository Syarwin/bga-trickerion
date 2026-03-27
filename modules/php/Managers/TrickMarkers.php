<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Models\TrickMarker;

class TrickMarkers extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'trick_marker';
    protected static $prefix = 'trick_marker_';
    protected static $customFields = ["player_id", "trick_marker_suit", "trick_id", "performance_slot_id" , "trick_marker_up_trick_type"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return new TrickMarker($raw);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "available" => self::getInLocation(self::LOCATION_AVAILABLE)->toArray(),
            "prepared" => self::getInLocation(self::LOCATION_PREPARED)->toArray(),
            "scheduled" => self::getInLocation(self::LOCATION_SCHEDULED)->toArray(),
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
        $trickMarkers = [];
        foreach (Players::getAll() as $playerId => $_) {
            foreach ([TrickMarker::SUIT_SPADES, TrickMarker::SUIT_HEARTS, TrickMarker::SUIT_DIAMONDS, TrickMarker::SUIT_CLUBS] as $suit) {
                $trickMarkers[] = [
                    'player_id' => $playerId,
                    'trick_marker_suit' => $suit,
                    'nbr' => 4,
                ];
            }
        }

        // Create the tricks
        self::create($trickMarkers, self::LOCATION_AVAILABLE, 0);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function getOnPerformance($performanceId) {
        return self::getInLocation(self::LOCATION_SCHEDULED, $performanceId);
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const LOCATION_AVAILABLE = 'available';
    const LOCATION_PREPARED = 'prepared';
    const LOCATION_SCHEDULED = 'scheduled';
}
