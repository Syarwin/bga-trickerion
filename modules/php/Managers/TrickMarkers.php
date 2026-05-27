<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Models\TrickMarker;

class TrickMarkers extends CachedPieces
{
    protected static ?Collection $datas = null;
    protected static string $table = 'trick_marker';
    protected static string $prefix = 'trick_marker_';
    protected static array $customFields = ["player_id", "trick_marker_suit", "trick_id", "performance_slot_id", "trick_marker_top_trick_category"];
    protected static bool $autoIncrement = true;
    protected static bool $autoremovePrefix = false;
    protected static bool $autoreshuffle = false;
    protected static array $autoreshuffleCustom = [];

    public static function autoreshuffleListener(string $location) {}

    protected static function cast(array $raw): TrickMarker
    {
        return new TrickMarker($raw);
    }

    public static function getUiData(?int $playerId = null): array
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

    public static function getOnPerformance($performanceId)
    {
        return self::getInLocation(self::LOCATION_SCHEDULED, $performanceId);
    }

    public static function getScheduled($playerId)
    {
        return self::getFiltered($playerId, self::LOCATION_SCHEDULED);
    }

    public static function getRescheduleData($playerId)
    {
        return self::getScheduled($playerId)
            ->map(function ($trickMarker) {
                $trickMarker->setLocation("temp");
                $performances = Performances::getActive()
                    ->filter(function ($performance) use ($trickMarker) {
                        return $performance->canAddTrick($trickMarker->getTrick());
                    });

                $trickMarker->setLocation(self::LOCATION_SCHEDULED);
                return $performances->map(function ($performance) {
                    return [
                        "performance" => $performance,
                        "possibleSlots" => $performance->getAvailableSlots(),
                    ];
                })->toAssoc();
            });
    }

    public static function returnToSupplies($trickMarkers)
    {
        $trickMarkers
            ->update("location", TrickMarkers::LOCATION_AVAILABLE)
            ->update("slotId", null)
            ->update("trickId", null)
            ->update("topTrickCategory", null)
            ->update("state", 0);
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
