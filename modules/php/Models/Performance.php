<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;


/**
 * Performance: model utilities and metadata for a performance.
 *
 * @property int $id The ID of the performance
 * @property string $type The type of the performance
 * @property int $location The location of the performance
 * @property string $name The name of the performance
 * @property string $theater The theater of the performance
 * @property object $slots The slots payload of the performance
 * @property object $bonus The bonus payload of the performance
 */
class Performance extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'performance';
    protected $primary = 'performance_id';
    protected $attributes = [
        'id' => ['performance_id', 'int'],
        'type' => ['performance_type', "string"],
        'location' => 'performance_location',
        'state' => ['performance_state', 'int'],
    ];

    protected $staticAttributes = [
        ['name', 'str'],
        ['theater', 'str'],
        ['slots', 'object'],
        ['bonus', 'object'],
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function getSlotDirections($slotId) {
        return array_map(function($link) {
            return $link["direction"];
        }, $this->getSlots()[$slotId]["links"]);
    }

    public function getSlotLink($slotId, $direction) {
        foreach ($this->getSlots()[$slotId]["links"] as $link) {
            if ($link["direction"] === $direction) {
                return $link;
            }
        }
        throw new \Exception("Link not found for slot $slotId and direction $direction");
    }

    /*
    ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */    

    const THEATER_RIVERSIDE = 'riverside_theater';
    const THEATER_GRAND_MAGORIAN = 'grand_magorian';
    const THEATER_MAGNUS_PANTHEON = 'magnus_pantheon';

    const LINK_DIRECTION_DOWN = 'down';
    const LINK_DIRECTION_RIGHT = 'right';
    const LINK_DIRECTION_UP = 'up';
    const LINK_DIRECTION_LEFT = 'left';
}
