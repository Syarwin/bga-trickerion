<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\TrickMarkers;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

/**
 * Tactics: all utility functions concerning a tactics
 * 
 * @property int $id The id of the tactics
 * @property string $type The type of the tactics
 * @property int $location The location of the tactics
 * @property int $state The state of the tactics
 * @property int $playerId The player id of the tactics
 * @property string $symbolMarker The symbol marker of the tactics
 * @property string $category The category of the tactics
 * @property string $name The name of the tactics
 * @property array $componentRequirements The component requirements of the tactics
 * @property int $preparationCost The preparation cost of the tactics
 * @property int $slots The number of slots of the tactics
 * @property int $level The level of the tactics
 * @property array $yields The yields of the tactics
 */
class Trick extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'trick';
    protected $primary = 'trick_id';
    protected $attributes = [
        'id' => ['trick_id', 'int'],
        'type' => ['trick_type', "string"],
        'location' => 'trick_location',
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
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

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
        return [
            1 => 1,
            2 => 16,
            3 => 36
        ][$this->getLevel()];
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
