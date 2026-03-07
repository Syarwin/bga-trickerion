<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;

/**
 * Class representing a Player
 *
 * @property int $fame The fame of the players (same as score)
 */
class Player extends \Bga\Games\trickerionlegendsofillusion\Framework\Models\Player
{
    protected $table = 'player';
    protected $primary = 'player_id';
    protected $customAttributes = [
        "shards" => ["player_shards", "int"],
        "coins" => ["player_coins", "int"],
        "initiative" => ["player_initiative", "int"],
        "colorName" => ["player_color_name", "str"],
    ];

    protected $staticAttributes = [
        ["fame", "int"]
    ];

    public function __construct($row)
    {
        parent::__construct($row);
        $this->fame = $row['player_score'];
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function incComponent(string $component, int $count, string $defaultLocation) {
        $component = Components::getAll()
            ->where("type", $component)
            ->where("playerId", $this->id)
            ->first();

        if ($component->getCount() === 0) {
            $component->setLocation($defaultLocation);
        }

        $component->incCount($count);

        Game::get()->bga->notify->all("componentChanged", clienttranslate('${player_name} gets ${count} ${component}'), [
            "player_id" => $this->id,
            "count" => $count,
            "component" => $component,
        ]);
    }

    public function hasEnoughComponents(string $componentType, int $count): bool {
        $component = Components::getAll()
            ->where("type", $componentType)
            ->where("playerId", $this->id)
            ->first();

        if (!$component->getLocation() === Components::LOCATION_MANAGER_BOARD){
            return ($component->getCount() + 1) >= $count; // +1 because we can use the component from the manager board 
        }

        return $component->getCount() >= $count;
    }
}
