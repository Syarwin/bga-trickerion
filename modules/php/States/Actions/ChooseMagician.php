<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\States\Actions;

use Bga\GameFramework\StateType;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\UserException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\AbstractNode;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\ActionStateWithRevert;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Globals;
use Bga\Games\trickerionlegendsofillusion\Managers\Magicians;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Constants\States;

class ChooseMagician extends ActionStateWithRevert
{
    function __construct(
        protected Game $game,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            node: $node,
            id: States::ST_CHOOSE_MAGICIAN,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose a magician'),
            descriptionMyTurn: clienttranslate('${you} must choose a magician'),
        );
    }

    public function getActionArgs(int $activePlayerId): array
    {
        $selectedMagicians = Magicians::getInLocation(Magicians::LOCATION_PLAYER);
        $selectedFavoriteTrickCategories = $selectedMagicians->map(fn($magician) => $magician->getFavoriteTrickCategory())->toArray();

        $args = [
            "availableMagicians" => Magicians::getInLocation(Magicians::LOCATION_AVAILABLE)
                ->whereNot("favoriteTrickCategory", $selectedFavoriteTrickCategories)
                ->toArray()
        ];
        return $args;
    }    

    /**
     * Player must resolve the choice.
     *
     * @throws UserException
     */
    #[PossibleAction]
    public function actChooseMagician(int $activePlayerId, int $magicianId)
    {
        Log::step();
        $magician = Magicians::get($magicianId);    
        if (!in_array($magician, $this->getActionArgs($activePlayerId)["availableMagicians"])) {
            throw new UserException("You cannot select this magician");
        }

        $magician->assignToPlayer($activePlayerId);

        Game::get()->notify->all("magicianChosen", clienttranslate('${player_name} has chosen a ${magician}'), [
            "player_id" => $activePlayerId,
            "magician" => $magician
        ]);

        if (Globals::isBeginnersSetup()) {
            $magician->doBeginnersSetup();
        } else {
            Engine::insertAsChild([
                "type" => Engine::NODE_PARALLEL,
                "children" => [
                    [
                        "state" => PickComponents::class,
                    ],
                    [
                        "state" => LearnTrick::class,
                        "args" => [
                            "sourceName" => clienttranslate("setup"),
                            "categories" => [ $magician->getFavoriteTrickCategory()]
                        ]
                    ],
                    [
                        "type" => Engine::NODE_SEQUENTIAL,
                        "customDescription" => clienttranslate('Hire character'),
                        "children" => [
                            [
                                "state" => HireCharacter::class,
                                "args" => [
                                    "sourceName" => clienttranslate("setup")
                                ]
                            ],
                            [
                                "state" => HiredCharacterSetup::class
                            ]
                        ]
                    ]
                ]
            ]);
        }

        return $this->resolve(["magicianId" => $magicianId]);
    }
}