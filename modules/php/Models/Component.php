<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\MarketRow;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

/**
 * Component: all utility functions concerning a component
 * 
 * @property int $id The id of the component
 * @property string $type The type of the component
 * @property string $location The location of the component
 * @property int $state The state of the component
 * @property int $playerId The player id of the component
 * @property int $count The count of the component
 * @property int $cost The cost of the component
 * @property string $name The name of the component
 */
class Component extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'component';
    protected $primary = 'component_id';
    protected $attributes = [
        'id' => ['component_id', 'int'],
        'type' => ['component_type', "string"],
        'location' => ['component_location', 'string'],
        'state' => ['component_state', 'int'],
        'playerId' => ['player_id', 'int'],
        'count' => ['component_count', 'int'],
    ];

    protected $staticAttributes = [
        ['cost', 'int'],
        ['name', 'string']
    ];

    public function __construct($row)
    {
        parent::__construct($row);
        $this->cost = self::getCostValue($row['component_type']);
        $this->name = self::getComponentName($row['component_type']);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function getCostValue(string $componentType): int
    {
        return match ($componentType) {
            self::WOOD, self::GLASS, self::METAL, self::FABRIC => 1,
            self::ROPE, self::PETROLEUM, self::SAW, self::ANIMAL => 2,
            self::PADLOCK, self::MIRROR, self::DISGUISE, self::COG => 3,
            default => throw new \InvalidArgumentException("Unknown component: $componentType"),
        };
    }

    public function getEffectiveCost()
    {
        $baseCost = $this->getCost();

        if ($this->getType() == MarketRow::getQuickOrder()) {
            $baseCost++;
        }

        return $baseCost;
    }

    public static function getComponentName(string $componentType): string
    {
        return match ($componentType) {
            self::WOOD => clienttranslate("wood"),
            self::GLASS => clienttranslate("glass"),
            self::METAL => clienttranslate("metal"),
            self::FABRIC => clienttranslate("fabric"),
            self::ROPE => clienttranslate("rope"),
            self::PETROLEUM => clienttranslate("petroleum"),
            self::SAW => clienttranslate("saw"),
            self::ANIMAL => clienttranslate("animal"),
            self::PADLOCK => clienttranslate("padlock"),
            self::MIRROR => clienttranslate("mirror"),
            self::DISGUISE => clienttranslate("disguise"),
            self::COG => clienttranslate("cog"),
            default => throw new \InvalidArgumentException("Unknown component: $componentType"),
        };
    }

    public function move(?int $toReplaceComponentId = null)
    {
        if ($toReplaceComponentId !== null) {
            $toReplaceComponent = Components::get($toReplaceComponentId);
            $toReplaceComponent->setLocation(Components::LOCATION_PLAYER_BOARD);
        }

        $this->setLocation(Components::LOCATION_MANAGER_BOARD);

        $message = $toReplaceComponentId === null
            ? clienttranslate('${player_name} moves <component>to the manager board')
            : clienttranslate('${player_name} moves <component>to the manager board, replacing ${secondComponent}');

        Game::get()->bga->notify->all("componentMoved", $message, [
            "player_id" => $this->getPlayerId(),
            "component" => $this,
            "secondComponent" => $toReplaceComponentId === null ? null : $toReplaceComponent
        ]);
    }

    public function getEffectiveCount()
    {
        $baseCount = $this->getCount();

        if ($this->getLocation() == Components::LOCATION_MANAGER_BOARD && $baseCount > 0) {
            $baseCount++;
        }

        return $baseCount;
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const WOOD = "wood";
    const GLASS = "glass";
    const METAL = "metal";
    const FABRIC = "fabric";

    const ROPE = "rope";
    const PETROLEUM = "petroleum";
    const SAW = "saw";
    const ANIMAL = "animal";

    const PADLOCK = "padlock";
    const MIRROR = "mirror";
    const DISGUISE = "disguise";
    const COG = "cog";
}
