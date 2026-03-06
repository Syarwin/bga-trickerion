<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

/**
 * Assignment: all utility functions concerning an assignment
 * 
 * @property int $id The id of the assignment
 * @property string $type The type of the assignment
 * @property int $location The location of the assignment
 * @property int $state The state of the assignment
 * @property int $playerId The player id of the assignment
 * @property string $boardLocation The board location of the assignment
 * @property string $category The category of the assignment
 * @property string $name The name of the assignment
 * @property string $targetAction The target action of the assignment
 * @property object $abilityText The ability text of the assignment
 */
class Assignment extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'assignment';
    protected $primary = 'assignment_id';
    protected $attributes = [
        'id' => ['assignment_id', 'int'],
        'type' => ['assignment_type', "string"],
        'location' => 'assignment_location',
        'state' => ['assignment_state', 'int'],
        'playerId' => ['player_id', 'int'],
    ];

    protected $staticAttributes = [
        ['boardLocation', 'str'],
        ['category', 'str'],
        ['name', 'str'],
        ['targetAction', 'str'],
        ['abilityText', 'obj'],
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

    const BOARD_LOCATION_THEATER = 'theater';
    const BOARD_LOCATION_DOWNTOWN = 'downtown'; 
    const BOARD_LOCATION_MARKET_ROW = 'market-row';
    const BOARD_LOCATION_WORKSHOP = 'workshop';
    const BOARD_LOCATION_DARK_ALLEY = 'dark-alley';

    const CATEGORY_SPECIAL = 'special';
    const CATEGORY_PERMANENT = 'permanent';

    const TARGET_ACTION_ANY = 'any';
    const TARGET_ACTION_SET_UP_TRICK = 'set-up-trick';
    const TARGET_ACTION_PERFORM = 'perform';
    const TARGET_ACTION_RESCHEDULE = 'reschedule';
    const TARGET_ACTION_PREPARE = 'prepare';
    const TARGET_ACTION_HIRE_CHARACTER = 'hire-character';
    const TARGET_ACTION_LEARN_TRICK = 'learn-trick';
    const TARGET_ACTION_TAKE_COINS = 'take-coins';
    const TARGET_ACTION_BUY = 'buy';
    const TARGET_ACTION_ORDER = 'order';
    const TARGET_ACTION_QUICK_ORDER = 'quick-order';

    const STATE_FACE_DOWN = 10;
    const STATE_FACE_UP = 20;
}
