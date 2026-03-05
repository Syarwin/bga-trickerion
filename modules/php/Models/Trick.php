<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

/**
 * Tactics: all utility functions concerning a tactics
 * 
 * @property int $id The id of the tactics
 * @property string $type The type of the tactics
 * @property int $location The location of the tactics
 * @property int $state The state of the tactics
 * @property int $playerId The player id of the tactics
 * @property string $symbolMarker The symbol marker of the tactics
 * @property string $category The category of the tactics
 * @property string $name The name of the tactics
 * @property array $componentRequirements The component requirements of the tactics
 * @property int $preparationCost The preparation cost of the tactics
 * @property int $slots The number of slots of the tactics
 * @property int $level The level of the tactics
 * @property array $yields The yields of the tactics
 */
class Trick extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'trick';
    protected $primary = 'trick_id';
    protected $attributes = [
        'id' => ['trick_id', 'int'],
        'type' => ['trick_type', "string"],
        'location' => 'trick_location',
        'state' => ['trick_state', 'int'],
        'playerId' => ['player_id', 'int'],
        'symbolMarker' => ['trick_symbol_marker', 'string'],
    ];

    protected $staticAttributes = [
        ['category', 'str'],
        ['name', 'str'],
        ['componentRequirements', 'object'],
        ['preparationCost', 'int'],
        ['slots', 'int'],
        ['level', 'int'],
        ['yields', 'object'],
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */    

   const CATEGORY_SPIRITUAL = 'spiritual';
   const CATEGORY_MECHANICAL = 'mechanical';
   const CATEGORY_ESCAPE = 'escape';
   const CATEGORY_OPTICAL = 'optical';
}
