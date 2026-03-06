<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;

class Globals extends \Bga\Games\trickerionlegendsofillusion\Framework\Db\Globals
{
    protected static $data = [];
    protected static $initialized = false;
    protected static $variables = [
        "marketRow" => "obj",
        "currentTurn" => "int"
    ];

    /*
    * Setup new game
    */
    public static function setupNewGame($players, $options)
    {
        MarketRow::init();
        MarketRow::setBuyArea([
            Components::WOOD,
            Components::METAL,
            Components::GLASS,
            Components::FABRIC,
        ]);

        self::setCurrentTurn(1);
    }

    public static function getUiData(int $playerId) {
        //it is possible to filter sam data here, if needed
        return array_merge(self::getAll(), [
            "isDarkAlley" => self::isDarkAlley(),
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

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
     ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const OPTION_DARK_ALLEY = 120;
}