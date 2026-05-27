<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class Tricks extends CachedPieces
{
    protected static ?Collection $datas = null;
    protected static string $table = 'trick';
    protected static string $prefix = 'trick_';
    protected static array $customFields = ["trick_type", "player_id", "trick_suit"];
    protected static bool $autoIncrement = true;
    protected static bool $autoremovePrefix = false;
    protected static bool $autoreshuffle = false;
    protected static array $autoreshuffleCustom = [];

    public static function autoreshuffleListener(string $location) {}

    protected static function cast(array $raw): Trick
    {
        return self::getTrickInstance($raw["trick_type"], $raw);
    }

    public static function getTrickInstance(string $type, ?array $data = null): Trick
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Tricks\\$type";
        return new $className($data);
    }

    public static function getUiData(?int $playerId = null): array
    {
        return [
            "available" => self::getInLocation(self::LOCATION_AVAILABLE)->toArray(),
            "player" => Players::getAll()->map(function ($player) {
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
    public static function getPreparebleTricks(int $playerId, $checkActionPoints = true)
    {
        $player = Players::get($playerId);

        return self::getFiltered($playerId, self::LOCATION_PLAYER_ALL)
            ->filter(function ($trick) use ($player, $checkActionPoints) {
                //check components
                if (!$trick->hasEnoughComponents()) {
                    return false;
                }

                //check that trick doesn't have markers on it already
                if ($trick->isPrepared()) {
                    return false;
                }

                //check that the player has enough AP if needed
                if ($checkActionPoints) {
                    $actionPointsNeeded = $trick->getPreparationCost();
                    if ($actionPointsNeeded > LocationActions::getRemainingActionPoints()) {
                        return false;
                    }
                }

                return true;
            });
    }

    public static function getPrepared($playerId)
    {
        return self::getFiltered($playerId, self::LOCATION_PLAYER_ALL)
            ->filter(function ($trick) {
                return $trick->isPrepared();
            });
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
    const LOCATION_PLAYER_ALL = "%-board";
    const LOCATION_PLAYER_BOARD = 'player-board';
    const LOCATION_ENGINEER_BOARD = 'engineer-board';
    const LOCATION_BOX = 'box';
}
