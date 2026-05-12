<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\TrickMarkers;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\States\Actions\GetCoins;
use Bga\Games\trickerionlegendsofillusion\States\Actions\GetFame;

/**
 * Trick: all utility functions concerning a trick
 * 
 * @property int $id The id of the trick
 * @property string $type The type of the trick
 * @property int $location The location of the trick
 * @property int $state The state of the trick
 * @property int $playerId The player id of the trick
 * @property string $symbolMarker The symbol marker of the trick
 * @property string $category The category of the trick
 * @property string $name The name of the trick
 * @property array $componentRequirements The component requirements of the trick
 * @property int $preparationCost The preparation cost of the trick
 * @property int $slots The number of slots of the trick
 * @property int $level The level of the trick
 * @property array $yields The yields of the trick
 * @property string $scoringDescription The scoring description of the trick
 */
class Trick extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'trick';
    protected $primary = 'trick_id';
    protected $attributes = [
        'id' => ['trick_id', 'int'],
        'type' => ['trick_type', "string"],
        'location' => ['trick_location', "string"],
        'state' => ['trick_state', 'int'],
        'playerId' => ['player_id', 'int'],
        'suit' => ['trick_suit', 'string'],
    ];

    protected $staticAttributes = [
        ['category', 'str'],
        ['name', 'str'],
        ['componentRequirements', 'object'],
        ['preparationCost', 'int'],
        ['slots', 'int'],
        ['level', 'int'],
        ['yields', 'object'],
        ['scoringDescription', 'object']
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function getPlayer() {
        return Players::get($this->getPlayerId());
    }

    public function learnTrick($playerId, $location) {
        $this->setLocation($location);
        $this->setPlayerId($playerId);

        $usedSuits = Tricks::getFiltered($playerId, Tricks::LOCATION_PLAYER_ALL)->pluck("suit")->toArray();

        //find first Trick marker suit that is not used
        $this->setSuit(TrickMarker::getFirstAvailableSuit($usedSuits));

        Game::get()->bga->notify->all("trickLearned", clienttranslate('${player_name} learns ${trick}'), [
            "player_id" => $playerId,
            "trick" => $this,
        ]);
    }

    public function getThreshold() {
        return match ($this->getLevel()) {
            1 => 1,
            2 => 16,
            3 => 36,
            default => throw new \Exception("Invalid trick level: " . $this->getLevel())
        };
    }

    public function getComponentsNeeded() {
        $cost = [];

        foreach ($this->componentRequirements as $component) {
            if (!isset($cost[$component])) {
                $cost[$component] = 0;
            }
            $cost[$component]++;
        }

        return $cost;
    }

    public static function getAllCategories() {
        return [
            self::CATEGORY_ESCAPE,
            self::CATEGORY_MECHANICAL,
            self::CATEGORY_OPTICAL,
            self::CATEGORY_SPIRITUAL,
        ];
    }

    public function getTrickMarkers() : Collection {
        return TrickMarkers::getAll()->where("trickId", $this->getId());
    }

    public function discard() {
        $trickMarkers = $this->getTrickMarkers()->update("trickId", null)->update("location", TrickMarkers::LOCATION_AVAILABLE);

        Game::get()->bga->notify->all("trickDiscarded", clienttranslate('${player_name} discards ${trick}, and puts all trick markers back to supply'), [
            "player_id" => $this->getPlayerId(),
            "trick" => $this,
            "markers" => $trickMarkers->toArray()
        ]);

        $this->setLocation(Tricks::LOCATION_AVAILABLE);
        $this->setPlayerId(null);
        $this->setSuit(null);
    }

    public function isPrepared() {
        return $this->getTrickMarkers()->where("location", TrickMarkers::LOCATION_PREPARED)->count() > 0;
    }

    public function prepare($spendActionPoints = true) {
        // assign tokens to the trick
        $slots = $this->getSlots();
        if ($this->getLocation() == Tricks::LOCATION_ENGINEER_BOARD && $slots < 4) {
            $slots += 1; // engineer space allows to prepare one additional trick marker
        }

        $markers = TrickMarkers::getFiltered($this->getPlayerId(), TrickMarkers::LOCATION_AVAILABLE)
            ->where("suit", $this->getSuit())
            ->limit($slots)
            ->update("location", TrickMarkers::LOCATION_PREPARED)
            ->update("trickId", $this->getId());

        $message = clienttranslate('${player_name} prepares ${trick} and adds ${count} markers');
        if ($spendActionPoints) {
            $message = clienttranslate('${player_name} prepares ${trick} for ${actionPoints} AP and adds ${count} markers');
        }

        Game::get()->bga->notify->all("trickPrepared", $message, [
            "player_id" => $this->getPlayerId(),
            "trick" => $this,
            "markers" => $markers->toArray(),
            "count" => $markers->count(),
            "actionPoints" => $spendActionPoints ? $this->getPreparationCost() : null,
        ]);
    }

    public function move() {
        $previousTrick = Tricks::getFiltered($this->getPlayerId(), Tricks::LOCATION_ENGINEER_BOARD)->first();
        if ($previousTrick) {
            $previousTrick->setLocation(Tricks::LOCATION_PLAYER_BOARD);
        }

        $this->setLocation(Tricks::LOCATION_ENGINEER_BOARD);

        $message = clienttranslate('${player_name} moves ${trick} to the engineer board');
        if ($previousTrick) {
            $message = clienttranslate('${player_name} moves ${trick} to the engineer board, replacing ${previousTrick}');
        }

        Game::get()->bga->notify->all("trickMoved", $message, [
            "player_id" => $this->getPlayerId(),
            "trick" => $this,
            "previousTrick" => $previousTrick
        ]);
    }

    public function setup(Performance $performance, string $slotId, string $direction) {
        //trick marker should be set on the performance slot
        $trickMarker = $this->getTrickMarkers()->where("location", TrickMarkers::LOCATION_PREPARED)->first();
        $trickMarker->addToPerformance($performance->getId(), $slotId, $direction);
        
        //reward for linking the same symbol
        //reward for shard in the link
        $performance->addLinkRewards($slotId);
    }

    public function hasEnoughComponents() {
        $cost = $this->getComponentsNeeded();

        $player = $this->getPlayer();
        foreach ($cost as $component => $count) {
            if (!$player->hasEnoughComponents($component, $count)) {
                return false;
            }
        }
        return true;
    }

    public function getLinkRewardActions() {
        return [
            "type" => Engine::NODE_XOR,
            "children" => [
                [
                    "state" => GetFame::class,
                    "args" => [
                        "amount" => $this->getLevel(),
                    ]
                ],
                [
                    "state" => GetCoins::class,
                    "args" => [
                        "amount" => $this->getLevel(),
                    ]
                ]
            ]
        ];
    }

    public function score() {
        if ($this->getLevel() < 3) {
            return 0;
        }

        if (!$this->hasEnoughComponents()) {
            return 0;
        }

        $score = min($this->calculateScore(), 20);
        Game::get()->bga->notify->all("message", clienttranslate('${player_name} is scoring ${trick} for ${fame} fame'), [
            "player_id" => $this->getPlayerId(),
            "trick" => $this,
            "fame" => $score
        ]);
        return $score;
    }

    public function calculateScore() {
        return 0; //should be overridden in trick classes
    }

    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */    

   const CATEGORY_SPIRITUAL = 'spiritual';
   const CATEGORY_MECHANICAL = 'mechanical';
   const CATEGORY_ESCAPE = 'escape';
   const CATEGORY_OPTICAL = 'optical';
}
