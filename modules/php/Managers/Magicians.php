<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Override;

class Magicians extends CachedPieces
{
    protected static string $table = 'magician';
    protected static string $prefix = 'magician_';
    protected static array $customFields = ["magician_type", "player_id"];
    protected static bool $autoIncrement = true;
    protected static bool $autoremovePrefix = false;
    protected static bool $autoreshuffle = false;
    protected static array $autoreshuffleCustom = [];

    public static function autoreshuffleListener($location) {}

    protected static function cast(array $raw): Magician
    {
        return self::getMagicianInstance($raw["magician_type"], $raw);
    }

    public static function getMagicianInstance(string $type, $data = null): Magician
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Magicians\\$type";
        return new $className($data);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "available" => self::getInLocation(self::LOCATION_AVAILABLE)->toArray(),
            "player" => Players::getAll()->map(function ($player) {
                return self::getInLocation(self::LOCATION_PLAYER)->where('playerId', $player->id)->first();
            }),
        ];
    }

    #[Override]
    public static function get($id, $raiseExceptionIfNotEnough = true): Magician
    {
        return parent::get($id, $raiseExceptionIfNotEnough);
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
        include dirname(__FILE__) . '/../Magicians/list.php';

        // Create cards
        $magicians = [];
        foreach ($magicianTypes as $type) {
            $data = [
                'magician_type' => $type,
            ];

            $magicians[] = $data;
        }

        // Create the magicians
        self::create($magicians, self::LOCATION_AVAILABLE, 0);
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
    const LOCATION_PLAYER = 'player';
}
