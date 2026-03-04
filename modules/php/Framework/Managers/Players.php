<?php

namespace Bga\Games\trickerionlegendsofillusion\Framework\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Models\Player;
use Bga\Games\trickerionlegendsofillusion\Game;

/*
 * Players manager : allows to easily access players ...
 *  a player is an instance of Player class
 */
class Players extends \Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedDB_Manager
{
    protected static $table = 'player';
    protected static $primary = 'player_id';
    protected static function cast($row)
    {
        return new \Bga\Games\trickerionlegendsofillusion\Framework\Models\Player($row);
    }

    public static function setupNewGame($players)
    {
        // Create players
        $gameInfos = Game::get()->getGameinfos();
        $colors = $gameInfos['player_colors'];
        $query = self::DB()->multipleInsert(['player_id', 'player_color', 'player_canal', 'player_name', 'player_avatar']);

        $values = [];
        foreach ($players as $pId => $player) {
            $color = \array_shift($colors);
            $values[] = [$pId, $color, $player['player_canal'], $player['player_name'], $player['player_avatar']];
        }
        $query->values($values);
        Game::get()->reattributeColorsBasedOnPreferences($players, $gameInfos['player_colors']);
        Game::get()->reloadPlayersBasicInfos();
        self::invalidate();
    }

    public static function getActiveId()
    {
        return (int) Game::get()->getActivePlayerId();
    }

    public static function getCurrentId()
    {
        return (int) Game::get()->getCurrentPId();
    }

    /*
   * get : returns the Player object for the given player ID
   */
    public static function get($pId = null) : ?Player
    {
        $pId = $pId ?: self::getActiveId();
        return parent::get($pId);
    }

    public static function getActive()
    {
        return self::get();
    }

    public static function getCurrent(): Player
    {
        return self::get(self::getCurrentId());
    }

    public static function getNextId($player)
    {
        $pId = is_int($player) ? $player : $player->getId();
        $table = Game::get()->getNextPlayerTable();
        return $table[$pId];
    }

    public static function getNext($player)
    {
        return self::get(self::getNextId($player));
    }

    public static function getPrevious($player)
    {
        $table = Game::get()->getPrevPlayerTable();
        $pId = (int) $table[$player->getId()];
        return self::get($pId);
    }

    /*
   * Return the number of players
   */
    public static function count()
    {
        return self::getAll()->count();
    }

    /*
   * getUiData : get all ui data of all players
   */
    public static function getUiData($playerId)
    {
        return self::getAll()
            ->map(function ($player) use ($playerId) {
                return $player->getUiData($playerId);
            })
            ->toAssoc();
    }

    /*
   * Get current turn order according to first player variable
   */
    public static function getTurnOrder($firstPlayer = null)
    {
        $firstPlayer = $firstPlayer ?? Game::get()->getNextPlayerTable()[0];
        $order = [];
        $p = $firstPlayer;
        do {
            $order[] = $p;
            $p = self::getNextId($p);
        } while ($p != $firstPlayer);
        return $order;
    }

    public static function getTurnOrderFiltered($availableIds, $firstPlayerId = null)
    {
        $firstPlayerId = $firstPlayerId ?? Game::get()->getNextPlayerTable()[0];
        $order = [];
        $p = $firstPlayerId;
        do {
            if (in_array($p, $availableIds)) {
                $order[] = $p;
            }
            $p = self::getNextId($p);
        } while ($p != $firstPlayerId);
        return $order;
    }

    public static function getOpponents($playerId) {
        return self::getAll()
            ->whereNot('id', $playerId);
    }

    public static function getOpponentsTurnOrder($playerId) {
        $opponents = self::getOpponents($playerId)->getIds();
        return self::getTurnOrderFiltered($opponents, $playerId);
    }
}
