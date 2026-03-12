<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Assignment;
use Bga\Games\trickerionlegendsofillusion\Models\Character;

class Characters extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'character';
    protected static $prefix = 'character_';
    protected static $customFields = ["player_id", "character_type"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    public static function cast($raw)
    {
        return new Character($raw);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "supply" => self::getInLocation(self::LOCATION_SUPPLY)->toArray(),
            "board" => self::getInLocation(self::LOCATION_BOARD_ANY)->toArray(),
            "idle" => self::getInLocation(self::LOCATION_IDLE_ANY)->toArray(),
            "hiredSpecialists" => Players::getAll()->map(function($player) {
                return self::getAll()
                    ->where('playerId', $player->id)
                    ->where('specialist', true)
                    ->whereNot('location', self::LOCATION_SUPPLY)
                    ->map(function($character) use ($player) {
                        return $character->getType();
                    })->toArray();
            }),
        ];
    }

    /*
  ███████╗███████╗████████╗██╗   ██╗██████╗
  ██╔════╝██╔════╝╚══██╔══╝██║   ██║██╔══██╗
  ███████╗█████╗     ██║   ██║   ██║██████╔╝
  ╚════██║██╔══╝     ██║   ██║   ██║██╔═══╝
  ███████║███████╗   ██║   ╚██████╔╝██║
  ╚══════╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝
  */

    /* Creation of the cards */
    public static function setupNewGame()
    {
        $characters = [];
        foreach (Players::getAll() as $playerId => $_) {
            foreach ([Character::TYPE_MAGICIAN, Character::TYPE_ENGINEER, Character::TYPE_MANAGER, Character::TYPE_ASSISTANT, Character::TYPE_APPRENTICE] as $type) {
                $characters[] = [
                    'player_id' => $playerId,
                    'character_type' => $type,
                    'nbr' => $type === Character::TYPE_APPRENTICE ? 4 : 1
                ];
            }
        }

        // Create the characters
        self::create($characters, self::LOCATION_SUPPLY, 0);

        foreach (Players::getAll() as $playerId => $_) {
            self::getFiltered($playerId, self::LOCATION_SUPPLY)
                ->where('type', Character::TYPE_MAGICIAN)
                ->first()
                ->setLocation(self::LOCATION_IDLE_PLAYER_BOARD);

            self::getFiltered($playerId, self::LOCATION_SUPPLY)
                ->where('type', Character::TYPE_APPRENTICE)
                ->first()
                ->setLocation(self::LOCATION_IDLE_PLAYER_BOARD);
        }
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function hire(string $type, int $playerId, string $location) {
        $character = self::getFiltered($playerId, Characters::LOCATION_SUPPLY)
            ->where("type", $type)
            ->first();

        if ($character->isSpecialist()) {
            $location = Character::getSpecialistLocation($type);
        }
        
        $character->setLocation($location);

        Game::get()->bga->notify->all("characterHired", clienttranslate('${player_name} hires ${character}'), [
            "player_id" => $playerId,
            "character" => $character
        ]);
    }

    public static function getPossibleLocations($type, $boardLocation) {
        $locations = [
            Assignment::BOARD_LOCATION_DOWNTOWN => [
                self::LOCATION_BOARD_DOWNTOWN_1,
                self::LOCATION_BOARD_DOWNTOWN_2,
                self::LOCATION_BOARD_DOWNTOWN_3,
                self::LOCATION_BOARD_DOWNTOWN_4
            ],
            Assignment::BOARD_LOCATION_MARKET_ROW => [
                self::LOCATION_BOARD_MARKET_ROW_1,
                self::LOCATION_BOARD_MARKET_ROW_2,
                self::LOCATION_BOARD_MARKET_ROW_3,
                self::LOCATION_BOARD_MARKET_ROW_4
            ],
            Assignment::BOARD_LOCATION_THEATER => 
                $type == Character::TYPE_MAGICIAN ? [
                    self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_THURSDAY_MAGICIAN,
                    self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_FRIDAY_MAGICIAN,
                    self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_SATURDAY_MAGICIAN,
                    self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_SUNDAY_MAGICIAN,
                ] : [
                    self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_2,
                    self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_1,
                    self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_2,
                ],
            Assignment::BOARD_LOCATION_WORKSHOP => [
                self::LOCATION_BOARD_WORKSHOP_1,
                self::LOCATION_BOARD_WORKSHOP_2,
            ],
            Assignment::BOARD_LOCATION_DARK_ALLEY => [
                self::LOCATION_BOARD_DARK_ALLEY_1,
                self::LOCATION_BOARD_DARK_ALLEY_2,
                self::LOCATION_BOARD_DARK_ALLEY_3,
                self::LOCATION_BOARD_DARK_ALLEY_4,
            ],
        ][$boardLocation];

        return new Collection($locations);
    }

    public static function isTheaterLocation($location) {
        return in_array($location, [
            self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_THURSDAY_MAGICIAN,
            self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_FRIDAY_MAGICIAN,
            self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_SATURDAY_MAGICIAN,
            self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_SUNDAY_MAGICIAN,
        ]);
    }

    public static function isWorkshopLocation($location) {
        return in_array($location, [
            self::LOCATION_BOARD_WORKSHOP_1,
            self::LOCATION_BOARD_WORKSHOP_2,
        ]);
    }

    private static function getLocationDay($location) {
        if (!self::isTheaterLocation($location)) {
            return null;
        }

        return explode("-", $location)[2];
    }

    public static function getTheaterPlayerForDay($location) {
        if (!self::isTheaterLocation($location)) {
            return null;
        }

        $day = self::getLocationDay($location);
        $character = self::getInLocation(self::LOCATION_BOARD_DAY_ANY($day))
            ->first();
            
        return is_null($character) ? null : $character->getPlayerId();
    }

    public static function getTheaterDayForPlayer($playerId) {
        $character = self::getFiltered($playerId, self::LOCATION_BOARD_THEATER_ANY)
            ->first();
            
        if ($character === null) {
            return null;
        }

        return self::getLocationDay($character->getLocation());
    }

    public static function getPerformingPlayers() {
        return Characters::getAll()
            ->where("location", self::LOCATION_BOARD_THEATER_MAGICIAN)
            ->reduce(function($carry, $character) {
                if ($character->getType() === Character::TYPE_MAGICIAN) {
                    $day = self::getLocationDay($character->getLocation());
                    $carry[$day] = $character->getPlayerId();
                }
                return $carry;
            }, []);
    }

    public static function return() {
        $allCharacters = self::getAll()
            ->whereNot("location", self::LOCATION_SUPPLY)
            ->update("location", self::LOCATION_IDLE_PLAYER_BOARD);

        $allCharacters->where("specialist", true)
            ->forEach(function($character) {
                $character->setLocation(Character::getSpecialistLocation($character->getType()));

                if ($character->getType() === Character::TYPE_ASSISTANT) {
                    Characters::getFiltered($character->getPlayerId(), self::LOCATION_IDLE_PLAYER_BOARD)
                        ->where("type", Character::TYPE_APPRENTICE)
                        ->first()
                        ->setLocation(self::LOCATION_IDLE_ASSISTANT_BOARD);
                }
            });

        Game::get()->bga->notify->all("charactersReturned", clienttranslate('All characters return to respective players boards'), [
            "characters" => $allCharacters->toArray(),
        ]);
    }

    public static function getLocationActionPoints($location) {
        return match ($location) {
            self::LOCATION_BOARD_DOWNTOWN_1, 
            self::LOCATION_BOARD_MARKET_ROW_1,
            self::LOCATION_BOARD_DARK_ALLEY_1 => 2,

            self::LOCATION_BOARD_DOWNTOWN_2, 
            self::LOCATION_BOARD_DOWNTOWN_3,
            self::LOCATION_BOARD_MARKET_ROW_2, 
            self::LOCATION_BOARD_MARKET_ROW_3,
            self::LOCATION_BOARD_DARK_ALLEY_2,
            self::LOCATION_BOARD_DARK_ALLEY_3,
            self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_1, 
            self::LOCATION_BOARD_THEATER_THURSDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_THURSDAY_MAGICIAN => 1,

            self::LOCATION_BOARD_WORKSHOP_1,
            self::LOCATION_BOARD_WORKSHOP_2,
            self::LOCATION_BOARD_DOWNTOWN_4,
            self::LOCATION_BOARD_MARKET_ROW_4,
            self::LOCATION_BOARD_DARK_ALLEY_4,
            self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_FRIDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_FRIDAY_MAGICIAN,
            self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_SATURDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_SATURDAY_MAGICIAN => 0,

            self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_1,
            self::LOCATION_BOARD_THEATER_SUNDAY_BASIC_2,
            self::LOCATION_BOARD_THEATER_SUNDAY_MAGICIAN => -1,

            default => 0
        };
    }

    public static function isCharacterHired($playerId, $characterType) {
        return self::getAll()
            ->where("playerId", $playerId)
            ->where("type", $characterType)
            ->whereNot("location", self::LOCATION_SUPPLY)
            ->count() > 0;
    }

    /*
     ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */

    const LOCATION_SUPPLY = 'supply';
    
    const LOCATION_IDLE_ANY = 'idle-%';
    const LOCATION_IDLE_PLAYER_BOARD = 'idle-player-board';
    const LOCATION_IDLE_MANAGER_BOARD = 'idle-manager-board';
    const LOCATION_IDLE_ASSISTANT_BOARD = 'idle-assistant-board';
    const LOCATION_IDLE_ENGINEER_BOARD = 'idle-engineer-board';
    
    const LOCATION_BOARD_ANY = 'board-%';
    const LOCATION_BOARD_THEATER_ANY = 'board-theater-%';
    const LOCATION_BOARD_THEATER_MAGICIAN = 'board-theater-%-magician';
    public static function LOCATION_BOARD_DAY_ANY($day) {
        return "board-theater-{$day}-%";
    }
    const LOCATION_BOARD_DOWNTOWN_1 = 'board-downtown-1';
    const LOCATION_BOARD_DOWNTOWN_2 = 'board-downtown-2';
    const LOCATION_BOARD_DOWNTOWN_3 = 'board-downtown-3';
    const LOCATION_BOARD_DOWNTOWN_4 = 'board-downtown-4';
    const LOCATION_BOARD_MARKET_ROW_1 = 'board-market-row-1';
    const LOCATION_BOARD_MARKET_ROW_2 = 'board-market-row-2';
    const LOCATION_BOARD_MARKET_ROW_3 = 'board-market-row-3';
    const LOCATION_BOARD_MARKET_ROW_4 = 'board-market-row-4';
    const LOCATION_BOARD_THEATER_THURSDAY_BASIC_1 = 'board-theater-thursday-basic-1';
    const LOCATION_BOARD_THEATER_THURSDAY_BASIC_2 = 'board-theater-thursday-basic-2';
    const LOCATION_BOARD_THEATER_THURSDAY_MAGICIAN = 'board-theater-thursday-magician';
    const LOCATION_BOARD_THEATER_FRIDAY_BASIC_1 = 'board-theater-friday-basic-1';
    const LOCATION_BOARD_THEATER_FRIDAY_BASIC_2 = 'board-theater-friday-basic-2';
    const LOCATION_BOARD_THEATER_FRIDAY_MAGICIAN = 'board-theater-friday-magician';
    const LOCATION_BOARD_THEATER_SATURDAY_BASIC_1 = 'board-theater-saturday-basic-1';
    const LOCATION_BOARD_THEATER_SATURDAY_BASIC_2 = 'board-theater-saturday-basic-2';
    const LOCATION_BOARD_THEATER_SATURDAY_MAGICIAN = 'board-theater-saturday-magician';
    const LOCATION_BOARD_THEATER_SUNDAY_BASIC_1 = 'board-theater-sunday-basic-1';
    const LOCATION_BOARD_THEATER_SUNDAY_BASIC_2 = 'board-theater-sunday-basic-2';
    const LOCATION_BOARD_THEATER_SUNDAY_MAGICIAN = 'board-theater-sunday-magician';
    const LOCATION_BOARD_WORKSHOP_1 = 'board-workshop-1';
    const LOCATION_BOARD_WORKSHOP_2 = 'board-workshop-2';
    const LOCATION_BOARD_DARK_ALLEY_1 = 'board-dark-alley-1';
    const LOCATION_BOARD_DARK_ALLEY_2 = 'board-dark-alley-2';
    const LOCATION_BOARD_DARK_ALLEY_3 = 'board-dark-alley-3';
    const LOCATION_BOARD_DARK_ALLEY_4 = 'board-dark-alley-4';
}
