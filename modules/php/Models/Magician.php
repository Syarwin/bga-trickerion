<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

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

    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */    
}
