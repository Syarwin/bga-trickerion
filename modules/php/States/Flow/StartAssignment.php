<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Flow;

use Bga\GameFramework\Actions\CheckAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\States;

class StartAssignment extends GameState
{
    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: States::ST_START_ASSIGNMENT,
            type: StateType::MULTIPLE_ACTIVE_PLAYER,
            description: clienttranslate('Other players must assign their characters'),
            descriptionMyTurn: clienttranslate('${you} must assign your characters'),
            initialPrivate: AssignCharacters::class,
        );
    }

    function onEnteringState(int $activePlayerId)
    {
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers(); 
    }   
    
    #[PossibleAction]
    #[CheckAction(false)]
    public function actChangeAssignment(int $currentPlayerId) {
        $this->gamestate->checkPossibleAction('actChangeAssignment');
        $this->game->gamestate->setPlayersMultiactive([$currentPlayerId], "", false);
        Assignments::resetAssignments($currentPlayerId);
        $this->game->gamestate->nextPrivateState($currentPlayerId, AssignCharacters::class);
    }
}