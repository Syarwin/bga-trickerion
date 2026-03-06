<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * Trickerion implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * Game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 */
declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion;

use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Framework\TurnOrderManager;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Magicians;
use Bga\Games\trickerionlegendsofillusion\Managers\Performances;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Prophecies;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\States\SetupTurn;

class Game extends \Bga\GameFramework\Table
{
    public static $instance = null;
    public static function get(): Game
    {
        return self::$instance;
    }

    /**
     * Your global variables labels:
     *
     * Here, you can assign labels to global variables you are using for this game. You can use any number of global
     * variables with IDs between 10 and 99. If you want to store any type instead of int, use $this->globals instead.
     *
     * NOTE: afterward, you can get/set the global variables with `getGameStateValue`, `setGameStateInitialValue` or
     * `setGameStateValue` functions.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initGameStateLabels([
            'logging' => 10,
        ]);

        self::$instance = $this;

        Engine::boot();
        Log::setResetCallback(function() {
            // Force to clear cached informations
            Globals::fetch();
            Players::invalidate();
        });

        $this->bga->notify->addDecorator(function($message, $args) {
            if (isset($args['player'])) {
                $args['player_name'] = $args['player']->getName();
                $args['player_id'] = $args['player']->getId();
                unset($args['player']);
            }
            if (isset($args['player2'])) {
                $args['player_name2'] = $args['player2']->getName();
                $args['player_id2'] = $args['player2']->getId();
                unset($args['player2']);
            }
            if (isset($args['players'])) {
                $args = [];
                $logs = [];
                foreach ($args['players'] as $i => $player) {
                    $logs[] = '${player_name' . $i . '}';
                    $args['player_name' . $i] = $player->getName();
                }
                $args['players_names'] = [
                    'log' => join(', ', $logs),
                    'args' => $args,
                ];
                $args['i18n'][] = 'players_names';
                unset($args['players']);
            }

            if (isset($args['player_id']) && !isset($args['player_name'])) {
                $args['player_name'] = Players::get($args['player_id'])->getName();
            }

            return $args;
        });
    }

    /**
     * Compute and return the current game progression.
     *
     * The number returned must be an integer between 0 and 100.
     *
     * This method is called each time we are in a game state with the "updateGameProgression" property set to true.
     *
     * @return int
     * @see ./states.inc.php
     */
    public function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }

    /**
     * Migrate database.
     *
     * You don't have to care about this until your game has been published on BGA. Once your game is on BGA, this
     * method is called everytime the system detects a game running with your old database scheme. In this case, if you
     * change your database scheme, you just have to apply the needed changes in order to update the game database and
     * allow the game to continue to run with your new version.
     *
     * @param int $from_version
     * @return void
     */
    public function upgradeTableDb($from_version)
    {
    }

    /*
     * Gather all information about current game situation (visible by the current player).
     *
     * The method is called each time the game interface is displayed to a player, i.e.:
     *
     * - when the game starts
     * - when a player refreshes the game page (F5)
     */
    public function getAllDatas($playerId = null): array
    {
        if ($playerId === null) {
            $playerId = Players::getCurrentId();
        }
        return [
            'players' => Players::getUiData($playerId),
            'globals' => Globals::getUiData($playerId),
            'tricks' => Tricks::getUiData($playerId),
            'performances' => Performances::getUiData($playerId),
            'assignments' => Assignments::getUiData($playerId),
            "prophecies" => Prophecies::getUiData($playerId),
            "magicians" => Magicians::getUiData($playerId),
        ];
    }

    /**
     * This method is called only once, when a new game is launched. In this method, you must setup the game
     *  according to the game rules, so that the game is ready to be played.
     */
    protected function setupNewGame($players, $options = [])
    {
        Globals::setupNewGame($players, $options);
        Players::setupNewGame($players);
        Tricks::setupNewGame();
        Performances::setupNewGame();
        Assignments::setupNewGame();
        Prophecies::setupNewGame();
        Magicians::setupNewGame();

        Log::enable();
        $this->activeNextPlayer();
        
        return TurnOrderManager::lauchDefault("turn", SetupTurn::class, null/*EndRound::class*/, false);
    }

    /////////////////////////////////////////////////////////////
    // Exposing protected methods, please use at your own risk //
    /////////////////////////////////////////////////////////////

    // Exposing protected method getCurrentPlayerId
    public function getCurrentPId()
    {
        return $this->getCurrentPlayerId();
    }

    /**
     * Example of debug function.
     * Here, jump to a state you want to test (by default, jump to next player state)
     * You can trigger it on Studio using the Debug button on the right of the top bar.
     */
    public function debug_goToState(int $state = 3) {
        $this->gamestate->jumpToState($state);
    }

}
