<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
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
        'onAssistantBoard' => ['character_on_assistant_board', 'bool'],
    ];

    protected $staticAttributes = [
        ['actionPoints', 'int'],
        ['name', 'str'],
        ['specialist', 'bool']
    ];

    public function __construct($row)
    {
        parent::__construct($row);
        $this->actionPoints = self::getCharacterActionPoints($this->type);
        $this->name = self::getCharacterName($this->type);
        $this->specialist = self::isSpecialistType($this->type);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */
    private static function isSpecialistType($type) {
        return in_array($type, [self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT]);
    }

    public static function getCharacterActionPoints($type) {
        return match ($type) {
            Character::TYPE_APPRENTICE => 1,
            Character::TYPE_ENGINEER, Character::TYPE_MANAGER, Character::TYPE_ASSISTANT => 2,
            Character::TYPE_MAGICIAN => 3,
            default => throw new \InvalidArgumentException("Unknown character type: $type"),
        };
    }

    private static function getCharacterName($type)
    {
        return [
            self::TYPE_MAGICIAN => clienttranslate('Magician'),
            self::TYPE_ENGINEER => clienttranslate('Engineer'),
            self::TYPE_MANAGER => clienttranslate('Manager'),
            self::TYPE_ASSISTANT => clienttranslate('Assistant'),
            self::TYPE_APPRENTICE => clienttranslate('Apprentice'),
        ][$type];
    }

    public static function getCharacterIdleLocation($type) {
        if (self::isSpecialistType($type)) {
            return self::getSpecialistLocation($type);
        }

        return Characters::LOCATION_IDLE_PLAYER_BOARD;
    }

    public function getIdleLocation() {
        if ($this->isOnAssistantBoard()) {
            return Characters::LOCATION_IDLE_ASSISTANT_BOARD;
        }

        return self::getCharacterIdleLocation($this->type);
    }

    public static function getSpecialistLocation($type) {
        return [
            self::TYPE_ENGINEER => Characters::LOCATION_IDLE_ENGINEER_BOARD,
            self::TYPE_MANAGER => Characters::LOCATION_IDLE_MANAGER_BOARD,
            self::TYPE_ASSISTANT => Characters::LOCATION_IDLE_ASSISTANT_BOARD,
        ][$type];
    }

    public function getPossibleLocations($boardLocation) {
        return Characters::getPossibleLocations($this->type, $boardLocation)
            ->filter(function($location) {
                return $this->canPlayerPlaceInLocation($this->playerId, $location);
            })->toArray();
    }

    private function canPlayerPlaceInLocation($playerId, $location) {
        $isOccupied = Characters::getInLocation($location)->first() !== null;

        if (Characters::isWorkshopLocation($location)) {
            $isOccupied = Characters::getFiltered($this->getPlayerId(), $location)->first() !== null;
        }

        if ($isOccupied) {
            return false;
        }

        if (Characters::isTheaterLocation($location)) {
            $playerInTheater = null;
            $playerInTheater = Characters::getTheaterPlayerForDay($location);

            if ($playerInTheater !== null && $playerInTheater != $this->playerId) {
                return false;
            }

            $day = Characters::getTheaterDayForPlayer($playerId);
            if ($day !== null && $day != explode("-", $location)[2]) {
                return false;
            }
        }            
        
        return true;
    }

    public static function getAllTypes() {
        return [self::TYPE_MAGICIAN, self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT, self::TYPE_APPRENTICE];
    }

    public function getAssignmentCard() {
        return Assignments::where("state", $this->getId())->first();
    }

    public function moveToAssistantBoard() {
        $this->setLocation(Characters::LOCATION_IDLE_ASSISTANT_BOARD);
        $this->setOnAssistantBoard(true);

        $attachedAssignment = $this->getAssignmentCard();

        Game::get()->notify->all("apprenticeMovedToAssistant", clienttranslate('${player_name} moves ${character} to their assistant board'), [
            "player_id" => $this->getPlayerId(),
            "character" => $this,
            "attachedAssignment" => $attachedAssignment,
        ]);
    }

    public function getWage($notifyAssistentDiscount = false) {
        if ($this->isOnAssistantBoard()) {
            if ($notifyAssistentDiscount) {
                Game::get()->notify->all("message", clienttranslate('${player_name} doesn\'t pay wage to ${name} as it is on the assistant board'), [
                    "player_id" => $this->getPlayerId(),
                    "name" => $this->getName(),
                ]);
            }
            return 0;
        }

        if (in_array($this->getLocation(), [
            Characters::LOCATION_SUPPLY, 
            Characters::LOCATION_IDLE_ENGINEER_BOARD,
            Characters::LOCATION_IDLE_MANAGER_BOARD,
            Characters::LOCATION_IDLE_ASSISTANT_BOARD,
            Characters::LOCATION_IDLE_PLAYER_BOARD
        ])) {
            return 0;
        }

        return match ($this->type) {
            self::TYPE_MAGICIAN => 0,
            self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT => 2,
            self::TYPE_APPRENTICE => 1,
            default => throw new \InvalidArgumentException("Unknown character type: {$this->type}"),
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

    const TYPE_MAGICIAN = 'magician';
    const TYPE_ENGINEER = 'engineer';
    const TYPE_MANAGER = 'manager';
    const TYPE_ASSISTANT = 'assistant';
    const TYPE_APPRENTICE = 'apprentice';
}
