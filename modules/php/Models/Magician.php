<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\Magicians;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

/**
 * Magician: all utility functions concerning a magician
 * 
 * @property int $id The id of the magician
 * @property string $type The type of the magician
 * @property string $location The location of the magician
 * @property int $state The state of the magician
 * @property int $playerId The player id of the magician
 * @property string $favoriteTrickCategory The favorite trick category of the magician
 * @property string $name The name of the magician
 * @property object $ability The ability of the magician
 */
class Magician extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'magician';
    protected $primary = 'magician_id';
    protected $attributes = [
        'id' => ['magician_id', 'int'],
        'type' => ['magician_type', "string"],
        'location' => 'magician_location',
        'state' => ['magician_state', 'int'],
        'playerId' => ['player_id', 'int'],
    ];

    protected $staticAttributes = [
        ['favoriteTrickCategory', 'str'],
        ['name', 'str'],
        ['ability', 'object'],
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function assignToPlayer(int $playerId)
    {
        $this->setPlayerId($playerId);
        $this->setLocation(Magicians::LOCATION_PLAYER);
        $this->setState(0);
    }

    public function doBeginnersSetup()
    {
        $setupData = self::beginnersSetupData($this->getFavoriteTrickCategory());

        $characters = $setupData["characters"] ?? [];
        foreach ($characters as $location => $characterTypes) {
            foreach ($characterTypes as $characterType) {
                Characters::hire($characterType, $this->getPlayerId(), $location);
            }
        }

        $tricks = $setupData["tricks"] ?? [];
        foreach ($tricks as $location => $trickTypes) {
            foreach ($trickTypes as $trickType) {
                $trick = Tricks::getAll()->where("type", $trickType)->first();
                $trick->learnTrick($this->getPlayerId(), $location);
            }
        }

        $components = $setupData["components"] ?? [];
        foreach ($components as $location => $componentTypes) {
            foreach ($componentTypes as $componentType) {
                Players::get($this->getPlayerId())->incComponent($componentType, 1, $location);
            }
        }
    }

    public static function beginnersSetupData($category)
    {
        return [
            Trick::CATEGORY_OPTICAL => [
                "tricks" => [
                    Tricks::LOCATION_PLAYER_BOARD => ["T01_EnchantedButterflies"],
                ],
                "components" => [
                    Components::LOCATION_PLAYER_BOARD => [Component::FABRIC, Component::FABRIC],
                    Components::LOCATION_MANAGER_BOARD => [Component::ANIMAL]
                ],
                "characters" => [
                    Characters::LOCATION_IDLE_MANAGER_BOARD => [Character::TYPE_MANAGER]
                ],
            ],
            Trick::CATEGORY_MECHANICAL => [
                "tricks" => [
                    Tricks::LOCATION_PLAYER_BOARD => ["T37_LinkingRings"],
                ],
                "components" => [
                    Components::LOCATION_PLAYER_BOARD => [Component::METAL, Component::METAL],
                ],
                "characters" => [
                    Characters::LOCATION_IDLE_ASSISTANT_BOARD => [Character::TYPE_ASSISTANT, Character::TYPE_APPRENTICE]
                ],
            ],
            Trick::CATEGORY_ESCAPE => [
                "tricks" => [
                    Tricks::LOCATION_PLAYER_BOARD => ["T25_BarricadedBarrels"],
                    Tricks::LOCATION_ENGINEER_BOARD => ["T26_StocksEscape"],
                ],
                "components" => [
                    Components::LOCATION_PLAYER_BOARD => [Component::WOOD, Component::WOOD],
                ],
                "characters" => [
                    Characters::LOCATION_IDLE_ENGINEER_BOARD => [Character::TYPE_ENGINEER]
                ],
            ],
            Trick::CATEGORY_SPIRITUAL => [
                "tricks" => [
                    Tricks::LOCATION_PLAYER_BOARD => ["T13_MindReading"],
                ],
                "components" => [
                    Components::LOCATION_PLAYER_BOARD => [Component::GLASS, Component::GLASS],
                    Components::LOCATION_MANAGER_BOARD => [Component::ROPE]
                ],
                "characters" => [
                    Characters::LOCATION_IDLE_MANAGER_BOARD => [Character::TYPE_MANAGER]
                ],
            ]
        ][$category] ?? [];
    }

    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */
}
