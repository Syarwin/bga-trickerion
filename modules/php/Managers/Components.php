<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Models\Component;

class Components extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'component';
    protected static $prefix = 'component_';
    protected static $customFields = ["player_id", "component_type", "component_count"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return new Component($raw);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "player" => Players::getAll()->map(function($player) {
                return self::getAll()
                    ->where('playerId', $player->id)
                    ->toArray();
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
        $characters = [];
        foreach (Players::getAll() as $playerId => $_) {
            foreach ([Component::WOOD, Component::GLASS, Component::METAL, Component::FABRIC, Component::ROPE, Component::PETROLEUM, Component::SAW, Component::ANIMAL, Component::PADDLOCK, Component::MIRROR, Component::DISGUISE, Component::COG] as $type) {
                $characters[] = [
                    'player_id' => $playerId,
                    'component_type' => $type,
                    'component_count' => 0,
                ];
            }
        }

        // Create the characters
        self::create($characters, self::LOCATION_PLAYER_BOARD, 0);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function getAffordableComponents(int $maxValue): array
    {
        $components = [];
        foreach ([Component::WOOD, Component::GLASS, Component::METAL, Component::FABRIC, Component::ROPE, Component::PETROLEUM, Component::SAW, Component::ANIMAL, Component::PADDLOCK, Component::MIRROR, Component::DISGUISE, Component::COG] as $component) {
            if (Component::getCost($component) <= $maxValue) {
                $components[] = $component;
            }
        }
        return $components;
    }

    public static function getLocationName(string $location): string
    {
        return match ($location) {
            self::LOCATION_PLAYER_BOARD => clienttranslate("player board"),
            self::LOCATION_MANAGER_BOARD => clienttranslate("manager board"),
            default => throw new \InvalidArgumentException("Unknown location: $location"),
        };
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const LOCATION_PLAYER_BOARD = "player-board";
    const LOCATION_MANAGER_BOARD = "manager-board";
}