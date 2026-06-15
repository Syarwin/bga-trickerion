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
 * @property string $idleLocation Where the character returns after the action phase
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
        'idleLocation' => ['character_idle_location', 'string'],
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
    private static function isSpecialistType(string $type)
    {
        return in_array($type, [self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT]);
    }

    public static function getCharacterActionPoints(string $type)
    {
        return match ($type) {
            Character::TYPE_APPRENTICE => 1,
            Character::TYPE_ENGINEER, Character::TYPE_MANAGER, Character::TYPE_ASSISTANT => 2,
            Character::TYPE_MAGICIAN => 3,
            default => throw new \InvalidArgumentException("Unknown character type: $type"),
        };
    }

    private static function getCharacterName(string $type)
    {
        return [
            self::TYPE_MAGICIAN => clienttranslate('Magician'),
            self::TYPE_ENGINEER => clienttranslate('Engineer'),
            self::TYPE_MANAGER => clienttranslate('Manager'),
            self::TYPE_ASSISTANT => clienttranslate('Assistant'),
            self::TYPE_APPRENTICE => clienttranslate('Apprentice'),
        ][$type];
    }

    /**
     * Returns the default idle location for a given character type, independent of game state.
     * This is used when creating characters and when no specific idle location has been set.
     */
    public static function getCharacterIdleLocation(string $type)
    {
        if (self::isSpecialistType($type)) {
            return self::getSpecialistLocation($type);
        }

        if ($type === Character::TYPE_APPRENTICE) {
            return Characters::getFreeApprenticeSlot();
        }

        return Characters::LOCATION_IDLE_PLAYER_BOARD;
    }

    /**
     * Returns where this character should return after the action phase.
     * Reads the stored idleLocation field directly from the DB.
     */
    public function getIdleLocation()
    {
        return $this->idleLocation;
    }

    public static function getSpecialistLocation(string $type)
    {
        return [
            self::TYPE_ENGINEER => Characters::LOCATION_IDLE_ENGINEER_BOARD,
            self::TYPE_MANAGER => Characters::LOCATION_IDLE_MANAGER_BOARD,
            self::TYPE_ASSISTANT => Characters::LOCATION_IDLE_ASSISTANT_BOARD,
        ][$type];
    }

    public function getPossibleLocations(string $boardLocation)
    {
        return Characters::getPossibleLocations($this->type, $boardLocation)
            ->filter(function ($location) {
                return $this->canPlayerPlaceInLocation($this->playerId, $location);
            })->toArray();
    }

    private function canPlayerPlaceInLocation(int $playerId, string $location)
    {
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

    public static function getAllTypes()
    {
        return [self::TYPE_MAGICIAN, self::TYPE_ENGINEER, self::TYPE_MANAGER, self::TYPE_ASSISTANT, self::TYPE_APPRENTICE];
    }

    public function getAssignmentCard()
    {
        return Assignments::where("state", $this->getId())->first();
    }

    public function moveToAssistantBoard()
    {
        $this->setIdleLocation(Characters::LOCATION_IDLE_ASSISTANT_BOARD);

        $attachedAssignment = $this->getAssignmentCard();

        Game::get()->notify->all("apprenticeMovedToAssistant", clienttranslate('${player_name} moves ${character} to their assistant board'), [
            "player_id" => $this->getPlayerId(),
            "character" => $this,
            "attachedAssignment" => $attachedAssignment,
        ]);
    }

    public function getWage($notifyAssistentDiscount = false)
    {
        // If on the assistant board (by idle location or current location), no wage
        $isOnAssistantBoard = $this->idleLocation === Characters::LOCATION_IDLE_ASSISTANT_BOARD;
        if ($isOnAssistantBoard) {
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
            Characters::LOCATION_IDLE_APPRENTICE_1,
            Characters::LOCATION_IDLE_APPRENTICE_2,
            Characters::LOCATION_IDLE_APPRENTICE_3,
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
    ██████╗ ██████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║   ██║   █████╗  
    ██║     ██║   ██║██║╚██╗██║   ██║   ██╔══╝  
    ╚██████╗╚██████╔╝██║ ╚████║   ██║   ███████╗
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const TYPE_MAGICIAN = 'magician';
    const TYPE_ENGINEER = 'engineer';
    const TYPE_MANAGER = 'manager';
    const TYPE_ASSISTANT = 'assistant';
    const TYPE_APPRENTICE = 'apprentice';
}
