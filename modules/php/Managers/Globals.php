<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Component;

class Globals extends \Bga\Games\trickerionlegendsofillusion\Framework\Db\Globals
{
    protected static $data = [];
    protected static $initialized = false;
    protected static $variables = [
        "marketRow" => "obj",
        "currentTurn" => "int",
        "pickingComponents" => "obj"
    ];

    /*
    * Setup new game
    */
    public static function setupNewGame($players, $options)
    {
        MarketRow::init();
        MarketRow::setBuyArea([
            Component::WOOD,
            Component::METAL,
            Component::GLASS,
            Component::FABRIC,
        ]);

        self::setCurrentTurn(1);
        self::setPickingComponents([]);
    }

    public static function getUiData(int $playerId) {
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

    public static function isDarkAlley() {
        $isDarkAlley = Game::$instance->tableOptions->get(Globals::OPTION_DARK_ALLEY);
        return $isDarkAlley == 1;
    }
    
    public static function isBeginnersSetup() {
        $isBeginnersSetup = Game::$instance->tableOptions->get(Globals::OPTION_BEGINNERS_SETUP);
        return $isBeginnersSetup == 1;
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
     ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const OPTION_DARK_ALLEY = 120;
    const OPTION_BEGINNERS_SETUP = 110;
}