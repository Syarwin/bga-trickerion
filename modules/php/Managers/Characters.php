<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Game;
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
            "occupied" => self::getInLocation(self::LOCATION_OCCUPIED_ANY)->toArray(),
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
    const LOCATION_OCCUPIED_ANY = 'occupied-%';
    
    const LOCATION_IDLE_PLAYER_BOARD = 'idle-player-board';
    const LOCATION_IDLE_MANAGER_BOARD = 'idle-manager-board';
    const LOCATION_IDLE_ASSISTANT_BOARD = 'idle-assistant-board';
    const LOCATION_IDLE_ENGINEER_BOARD = 'idle-engineer-board';

    const LOCATION_OCCUPIED_DOWNTOWN = 'occupied-downtown';
    const LOCATION_OCCUPIED_MARKET_ROW = 'occupied-market-row';
    const LOCATION_OCCUPIED_THEATER = 'occupied-theater';
    const LOCATION_OCCUPIED_WORKSHOP = 'occupied-workshop';
    const LOCATION_OCCUPIED_DARK_ALLEY = 'occupied-dark-alley';
}
