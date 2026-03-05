<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

class MarketRow
{
    public static function init() {
        Globals::setMarketRow([
            "buyArea" => [],
            "quickOrder" => null,
            "orderArea" => []
        ]);
    }

    public static function setBuyArea(array $components) {
        $marketRow = Globals::getMarketRow();
        $marketRow["buyArea"] = $components;
        Globals::setMarketRow($marketRow);
    }
}