<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Game;

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

    public static function roundMaintenance() {
        if (!Globals::isIncludeProphecies()) {
            return;
        }

        // Active prophecies are discarded
        $activeProphecy = self::getInLocation(self::LOCATION_ACTIVE)->first();
        if ($activeProphecy) {
            $activeProphecy->setLocation(self::LOCATION_DISCARD);
            $activeProphecy->setState(0);

            Game::get()->notify->all("activeProphecyDiscarded", clienttranslate('The active prophecy is discarded: ${prophecy}'), [
                'prophecy' => $activeProphecy,
            ]);
        }

        $newActiveProphecy = self::getTopOf(self::LOCATION_PENDING)->first();
        if ($newActiveProphecy) {
            $newActiveProphecy->setLocation(self::LOCATION_ACTIVE);
            $newActiveProphecy->setState(0);

            Game::get()->notify->all("activeProphecySet", clienttranslate('A new active prophecy: ${prophecy}'), [
                'prophecy' => $newActiveProphecy,
            ]);
        }

        $pendingProphecies = self::getInLocation(self::LOCATION_PENDING)->forEach(function($prophecy) {
            $prophecy->setState($prophecy->getState() + 1);
        });

        Game::get()->notify->all("pendingPropheciesRotated", clienttranslate('Pending prophecies have been rotated clockwise.'), [
            "prophecies" => $pendingProphecies,
        ]);

        $newPendingProphecy = self::pickForLocation(1, self::LOCATION_DECK, self::LOCATION_PENDING, 1)->first();
        Game::get()->notify->all("newPendingProphecyRevealed", clienttranslate('A new pending prophecy card is revealed: ${prophecy}'), [
            "prophecy" => $newPendingProphecy,
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
    const LOCATION_PENDING = 'pending';
    const LOCATION_DISCARD = 'discard';
}
