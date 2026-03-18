<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
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

    public static function cast($raw)
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
        $components = [];
        foreach (Players::getAll() as $playerId => $_) {
            foreach (self::getAllComponents() as $type) {
                $components[] = [
                    'player_id' => $playerId,
                    'component_type' => $type,
                    'component_count' => 0,
                ];
            }
        }

        // Create the components
        self::create($components, self::LOCATION_PLAYER_BOARD, 0);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function getAllComponents(): array
    {
        return [Component::WOOD, Component::GLASS, Component::METAL, Component::FABRIC, Component::ROPE, Component::PETROLEUM, Component::SAW, Component::ANIMAL, Component::PADDLOCK, Component::MIRROR, Component::DISGUISE, Component::COG];
    }

    public static function getAffordableComponents(int $maxValue, ?array $limit = null): array
    {
        if (is_null($limit)) {
            $limit = self::getAllComponents();
        }

        $components = [];
        foreach ($limit as $component) {
            if (Component::getCostValue($component) <= $maxValue) {
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

    public static function getAvailableSlots(string $location, int $playerId): int
    {
        $player = Players::get($playerId);
        $usedSlotCounts = self::getFiltered($playerId, $location)->count();
        $totalSlotCounts = $location === self::LOCATION_PLAYER_BOARD ? 6 : ($player->hasSpecialist(Character::TYPE_MANAGER) ? 2 : 0);
        return $totalSlotCounts - $usedSlotCounts;
    }

    public static function getMaxCounts(array $components, int $playerId): array
    {
        $maxCounts = [];
        $player = Players::get($playerId);
        foreach ($components as $component) {
            $component = self::getAll()
                ->where("type", $component)
                ->where("playerId", $playerId)
                ->first();

            $playerAvailableLocations = [Components::LOCATION_PLAYER_BOARD];
            if ($player->hasSpecialist(Character::TYPE_MANAGER)) {
                $playerAvailableLocations[] = Components::LOCATION_MANAGER_BOARD;
            }

            $availableLocations = $component->getCount() > 0 ? [$component->getLocation()] : $playerAvailableLocations;
            
            $maxCounts[$component->getType()] = [];
            
            $playerCoins = $player->getCoins();
            $componentCost = $component->getEffectiveCost();
            $maxAffordableByCoins = floor($playerCoins / $componentCost);

            foreach ($availableLocations as $location) {
                $maxSpace = $location == Components::LOCATION_PLAYER_BOARD ? 3 : 2;
                $availableSpace = $maxSpace - $component->getCount();

                $maxCounts[$component->getType()][$location] = [
                    'max' => min($availableSpace, $maxAffordableByCoins),
                    "effectiveCost" => $componentCost
                ];
            }
        }
        return $maxCounts;
    }

    public static function addComponent(int $playerId, string $component, string $locationId, int $count, int $bargain) {
        $component = self::getAll()
            ->where("type", $component)
            ->where("playerId", $playerId)
            ->first();

        $component->incCount($count);
        $component->setLocation($locationId); 

        $cost = $component->getEffectiveCost() * $count - $bargain;
        Players::get($playerId)->incCoins(-$cost);
        LocationActions::incActionPoints(-$bargain);

        $message = clienttranslate('${player_name} bought ${count} ${component} for ${cost} and placed it at the ${location}');
        if ($bargain > 0) {
            $message = clienttranslate('${player_name} bought ${count} ${component} for ${cost} (after bargaining ${bargain} coins) and placed it at the ${location}');
        }

        Game::get()->bga->notify->all("componentBought", $message, [
            "player_id" => $playerId,
            "component" => $component,
            "count" => $count,
            "location" => self::getLocationName($locationId),
            "cost" => $cost,
            "bargain" => $bargain
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

    const LOCATION_PLAYER_BOARD = "player-board";
    const LOCATION_MANAGER_BOARD = "manager-board";
    const LOCATION_PLAYER_ANY = "%-board";
}