<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;

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

        Game::get()->bga->notify->all("marketRowSet", clienttranslate('New components available in the market row: ${components}'), [
            "components" => $components
        ]);
    }

    public static function getBuyArea(): array {
        $marketRow = Globals::getMarketRow();
        return $marketRow["buyArea"];
    }

    public static function setQuickOrder($component) {
        $marketRow = Globals::getMarketRow();
        $marketRow["quickOrder"] = $component;
        Globals::setMarketRow($marketRow);

        Game::get()->bga->notify->all("marketRowSet", clienttranslate('${player_name} made a new component available through the quick order: ${component}'), [
            "player_id" => Players::getActiveId(),
            "component" => $component
        ]);
    }

    public static function getQuickOrder() {
        $marketRow = Globals::getMarketRow();
        return $marketRow["quickOrder"];
    }

    public static function getBuyableComponents() {
        $buyArea = self::getBuyArea();
        $quickOrder = self::getQuickOrder();
        if (!is_null($quickOrder)) {
            $buyArea[] = $quickOrder;
        }
        return $buyArea;
    }
}