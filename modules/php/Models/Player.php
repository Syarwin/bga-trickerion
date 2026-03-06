<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;

/**
 * Class representing a Player
 *
 */
class Player extends \Bga\Games\trickerionlegendsofillusion\Framework\Models\Player
{
    protected $table = 'player';
    protected $primary = 'player_id';
    protected $customAttributes = [
        Components::WOOD => ["player_component_" . Components::WOOD, "int"],
        Components::GLASS => ["player_component_" . Components::GLASS, "int"],
        Components::METAL => ["player_component_" . Components::METAL, "int"],
        Components::FABRIC => ["player_component_" . Components::FABRIC, "int"],
        
        Components::ROPE => ["player_component_" . Components::ROPE, "int"],
        Components::PETROLEUM => ["player_component_" . Components::PETROLEUM, "int"],
        Components::SAW => ["player_component_" . Components::SAW, "int"],
        Components::ANIMAL => ["player_component_" . Components::ANIMAL, "int"],

        Components::PADDLOCK => ["player_component_" . Components::PADDLOCK, "int"],
        Components::MIRROR => ["player_component_" . Components::MIRROR, "int"],
        Components::DISGUISE => ["player_component_" . Components::DISGUISE, "int"],
        Components::COG => ["player_component_" . Components::COG, "int"],

        "shards" => ["player_shards", "int"],
        "coins" => ["player_coins", "int"],
        "initiative" => ["player_initiative", "int"],
        "colorName" => ["player_color_name", "str"],
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function setComponent($component, $value) {
        $method = "set" . \ucfirst($component);
        self::$method($value);
    }

    public static function incComponent($component, $value) {
        $method = "inc" . \ucfirst($component);
        self::$method($value);
    }
}
