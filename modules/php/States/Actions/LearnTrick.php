<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;

class LearnTrick extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_LEARN_TRICK,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must learn a trick'),
            descriptionMyTurn: clienttranslate('${you} must learn a trick'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must learn a trick (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must learn a trick (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Learn a trick (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Learn a trick');
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableTricks = Tricks::getInLocation(Tricks::LOCATION_AVAILABLE);

        $availableCategories = $this->getNodeArgs("categories", null);
            
        if (!is_null($availableCategories)) {
            $availableTricks = $availableTricks->where("category", $availableCategories);
        }

        $player = Players::get($activePlayerId);
        $availableTricks = $availableTricks->filter(fn($trick) => $trick->getThreshold() <= $player->getScore());

        $args = [
            "availableTricks" => $availableTricks->toArray(),
            "sourceName" => $this->getNodeArgs("sourceName")
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actLearnTrick(int $activePlayerId, int $trickId)
    {
        Log::step();
        /* @var Trick $trick */
        $trick = Tricks::get($trickId);
        $availableTricks = $this->getActionArgs($activePlayerId)["availableTricks"];
        if (!in_array($trick, $availableTricks, true)) {
            throw new UserException(clienttranslate("You cannot take this trick."));
        }

        $location = $this->getNodeArgs("location", Tricks::LOCATION_PLAYER_BOARD);

        $trick->learnTrick($activePlayerId, $location);
        return $this->resolve(["trickId" => $trickId]);
    }

    /**
     * This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
     * You can do whatever you want in order to make sure the turn of this player ends appropriately
     * (ex: play a random card).
     * 
     * See more about Zombie Mode: https://en.doc.boardgamearena.com/Zombie_Mode
     *
     * Important: your zombie code will be called when the player leaves the game. This action is triggered
     * from the main site and propagated to the gameserver from a server, not from a browser.
     * As a consequence, there is no current player associated to this action. In your zombieTurn function,
     * you must _never_ use `getCurrentPlayerId()` or `getCurrentPlayerName()`, 
     * but use the $playerId passed in parameter and $this->game->getPlayerNameById($playerId) instead.
     */
    function zombie(int $playerId) {
        
    }    
}