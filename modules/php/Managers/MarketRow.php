<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Component;

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

        Game::get()->bga->notify->all("buyAreaSet", clienttranslate('New components available in the market row: ${components}'), [
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

        Game::get()->bga->notify->all("quickOrderSet", clienttranslate('${player_name} made a new component available through the quick order: ${componentName}'), [
            "player_id" => Players::getActiveId(),
            "componentName" => Component::getComponentName($component),
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

    public static function getOrderableComponents() {
        //all components that are not in buy area or order area
        $marketRow = Globals::getMarketRow();
        $unavailableComponents = array_merge($marketRow["buyArea"], $marketRow["orderArea"]);

        $allComponents = Components::getAllComponents();
        $orderableComponents = array_values(array_diff($allComponents, $unavailableComponents));
        return $orderableComponents;
    }
    
    public static function getComponentsForQuickOrder() {
        //all components that are not in buy area or order area
        $marketRow = Globals::getMarketRow();
        $unavailableComponents = $marketRow["buyArea"];

        if (!is_null($marketRow["quickOrder"])) {
            $unavailableComponents[] = $marketRow["quickOrder"];
        }

        $allComponents = Components::getAllComponents();
        $componentsForQuickOrder = array_values(array_diff($allComponents, $unavailableComponents));
        return $componentsForQuickOrder;
    }

    public static function getEmptyOrderSlots() {
        $marketRow = Globals::getMarketRow();
        $usedSlots = array_keys($marketRow["orderArea"]);
        $allSlots = [0, 1, 2, 3];

        return array_values(array_diff($allSlots, $usedSlots));
    }

    public static function addToOrder(string $component, int $slot) {
        $marketRow = Globals::getMarketRow();
        $marketRow["orderArea"][$slot] = $component;
        Globals::setMarketRow($marketRow);

        Game::get()->bga->notify->all("componentOrdered", clienttranslate('${player_name} ordered ${componentName}'), [
            "player_id" => Players::getActiveId(),
            "componentName" => Component::getComponentName($component),
            "slot" => $slot,
            "component" => $component
        ]);
    }

    public static function ordersArrive() {
        $marketRow = Globals::getMarketRow();
        $orderArea = $marketRow["orderArea"];
        $marketRow["orderArea"] = [];

        foreach ($orderArea as $slot => $component) {
            $previouslyAvailableComponent = $marketRow["buyArea"][$slot];
            $marketRow["buyArea"][$slot] = $component;

            Game::get()->bga->notify->all("componentArrived", clienttranslate('${componentId} replaces ${secondComponentId} in the market row (orders arrive)'), [
                "componentId" => $component,
                "secondComponentId" => $previouslyAvailableComponent,
                "slot" => $slot
                
            ]);
        }

        Globals::setMarketRow($marketRow);

        return $orderArea;
    }

    public static function clearQuickOrder() {
        $marketRow = Globals::getMarketRow();
        $previousQuickOrder = $marketRow["quickOrder"];
        $marketRow["quickOrder"] = null;
        Globals::setMarketRow($marketRow);

        Game::get()->bga->notify->all("quickOrderCleared", clienttranslate('${componentId} is removed from the quick order'), [
            "componentId" => $previousQuickOrder
        ]);
    }
}