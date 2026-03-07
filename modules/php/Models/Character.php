<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Managers\Characters;

/**
 * Character: all utility functions concerning a character
 * 
 * @property int $id The id of the character
 * @property string $location The location of the character
 * @property int $state The state of the character
 * @property string $type The type of the character
 * @property int $playerId The player id of the character
 * @property int $actionPoints The action points of the character
 * @property string $name The name of the character
 * @property bool $specialist Whether the character is a specialist
 */
class Character extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'character';
    protected $primary = 'character_id';
    protected $attributes = [
        'id' => ['character_id', 'int'],
        'location' => 'character_location',
        'state' => ['character_state', 'int'],
        'playerId' => ['player_id', 'int'],
        'type' => ['character_type', 'string'],
    ];

    protected $staticAttributes = [
        ['actionPoints', 'int'],
        ['name', 'str'],
        ['specialist', 'bool']
    ];

    public function __construct($row)
    {
        parent::__construct($row);
        $this->actionPoints = self::getActionPoints($this->type);
        $this->name = self::getName($this->type);
        $this->specialist = in_array($this->type, [self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT]);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    private static function getActionPoints($type)
    {
        return [
            self::TYPE_MAGICIAN => 3,
            self::TYPE_ENGINEER => 2,
            self::TYPE_MANAGER => 2,
            self::TYPE_ASSISTANT => 2,
            self::TYPE_APPRENTICE => 1,
        ][$type];
    }   

    private static function getName($type)
    {
        return [
            self::TYPE_MAGICIAN => clienttranslate('Magician'),
            self::TYPE_ENGINEER => clienttranslate('Engineer'),
            self::TYPE_MANAGER => clienttranslate('Manager'),
            self::TYPE_ASSISTANT => clienttranslate('Assistant'),
            self::TYPE_APPRENTICE => clienttranslate('Apprentice'),
        ][$type];
    }

    public static function getSpecialistLocation($type) {
        return [
            self::TYPE_ENGINEER => Characters::LOCATION_IDLE_ENGINEER_BOARD,
            self::TYPE_MANAGER => Characters::LOCATION_IDLE_MANAGER_BOARD,
            self::TYPE_ASSISTANT => Characters::LOCATION_IDLE_ASSISTANT_BOARD,
        ][$type];
    }

    /*
    ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */    

    const TYPE_MAGICIAN = 'magician';
    const TYPE_ENGINEER = 'engineer';
    const TYPE_MANAGER = 'manager';
    const TYPE_ASSISTANT = 'assistant';
    const TYPE_APPRENTICE = 'apprentice';
}
