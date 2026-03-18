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
use Bga\Games\trickerionlegendsofillusion\Managers\TrickMarkers;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;
use Bga\Games\trickerionlegendsofillusion\Constants\States;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;

class PrepareTrick extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_PREPARE_TRICK,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must prepare a trick'),
            descriptionMyTurn: clienttranslate('${you} must prepare a trick'),
        );
    }

    public function getCustomStateDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "description" => clienttranslate('${actplayer} must prepare a trick (${sourceName})'),
                "descriptionMyTurn" => clienttranslate('${you} must prepare a trick (${sourceName})'),
            ];
        }
        return null;
    }

    public function getDescription() {
        if (!is_null($this->getNodeArgs("sourceName"))) {
            return [
                "log" => clienttranslate('Prepare a trick (${sourceName})'),
                "args" => [
                    "sourceName" => $this->getNodeArgs("sourceName", "")
                ]
            ];
        }
        return clienttranslate('Prepare a trick');
    }

    public function isOptional(): bool {
        $availableTricks = $this->getActionArgs(Players::getActiveId())["availableTricks"] ?? [];
        return count($availableTricks) === 0;
    }

    public function isAutomatic() {
        return $this->getNodeArgs("auto", false);
    }

    public function onEnteringState(int $activePlayerId) {
        if ($this->isAutomatic()) {
            $availableTricks = Tricks::getPreparebleTricks($activePlayerId, false);
    
            $availableTricks->forEach(function(Trick $trick) {
                $trick->prepare(false);
            });
    
            return $this->resolve(["trickIds" => $availableTricks->getIds()]);
        }
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $availableTricks = Tricks::getPreparebleTricks($activePlayerId);

        $args = [
            "availableTricks" => $availableTricks->toArray(),
            "sourceName" => $this->getNodeArgs("sourceName", "")
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actPrepareTrick(int $activePlayerId, int $trickId, array $args)
    {
        Log::step();
        /* @var Trick $trick */
        $trick = Tricks::get($trickId);
        $availableTricks = $args["availableTricks"];
        if (!in_array($trick, $availableTricks, true)) {
            throw new UserException(clienttranslate("You cannot prepare this trick."));
        }

        $trick->prepare();

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