<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Component;

class Globals extends \Bga\Games\trickerionlegendsofillusion\Framework\Db\Globals
{
    protected static array $data = [];
    protected static bool $initialized = false;
    protected static array $variables = [
        "marketRow" => "obj",
        "currentTurn" => "int",
        "pickingComponents" => "obj",
        "dice" => "obj",
        "locationActions" => "obj",
        "drawAssignmentCardsAction" => "obj"
    ];

    /*
    * Setup new game
    */
    public static function setupNewGame(array $players, array $options): void
    {
        MarketRow::init();
        MarketRow::setBuyArea([
            Component::WOOD,
            Component::METAL,
            Component::GLASS,
            Component::FABRIC,
        ]);

        Dice::init();
        LocationActions::init();
        DrawAssignmentCardsAction::init();

        self::setCurrentTurn(1);
        self::setPickingComponents([]);
    }

    public static function getUiData(int $playerId): array
    {
        //it is possible to filter sam data here, if needed
        return array_merge(self::getAll(), [
            "isDarkAlley" => self::isDarkAlley(),
            "isBeginnersSetup" => self::isBeginnersSetup(),
        ]);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function isDarkAlley()
    {
        $isDarkAlley = Game::$instance->tableOptions->get(Globals::OPTION_DARK_ALLEY);
        return $isDarkAlley == 1;
    }

    public static function isBeginnersSetup()
    {
        $isBeginnersSetup = Game::$instance->tableOptions->get(Globals::OPTION_BEGINNERS_SETUP);
        return $isBeginnersSetup == 1;
    }

    public static function isIncludeProphecies()
    {
        $isIncludeProphecies = Game::$instance->tableOptions->get(Globals::OPTION_INCLUDE_PROPHECIES);
        return $isIncludeProphecies == 1 && self::isDarkAlley();
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
     ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const OPTION_BEGINNERS_SETUP = 110;
    const OPTION_DARK_ALLEY = 120;
    const OPTION_INCLUDE_PROPHECIES = 125;
}
