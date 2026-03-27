<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

/**
 * Trick Marker: all utility functions concerning a trick marker
 * 
 * @property int $id The id of the trick marker
 * @property string $location The location of the trick marker
 * @property int $state The state of the trick marker
 * @property string $suit The suit of the trick marker
 * @property int $playerId The player id of the trick marker
 */
class TrickMarker extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'trick_marker';
    protected $primary = 'trick_marker_id';
    protected $attributes = [
        'id' => ['trick_marker_id', 'int'],
        'location' => 'trick_marker_location',
        'state' => ['trick_marker_state', 'int'],
        'suit' => ['trick_marker_suit', "string"],
        'playerId' => ['player_id', 'int'],
        'trickId' => ['trick_id', 'int'],
        'slotId' => ['performance_slot_id', 'string'],
        'topTrickCategory' => ['trick_marker_top_trick_category', 'string'],
    ];

    protected $staticAttributes = [];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function getFirstAvailableSuit($usedSymbols) {
         $allSuits = [TrickMarker::SUIT_SPADES, TrickMarker::SUIT_HEARTS, TrickMarker::SUIT_DIAMONDS, TrickMarker::SUIT_CLUBS];
         foreach ($allSuits as $suit) {
             if (!in_array($suit, $usedSymbols, true)) {
                 return $suit;
             }
         }
         throw new \Exception("No available suit found");
    }

    public function getTrick() {
        return Tricks::get($this->getTrickId());
    }

    public function setDirection(string $direction) {
        $trickCategory = $this->getTrick()->getCategory();
        $topTrickCategory = match ($direction) {
            Performance::LINK_DIRECTION_RIGHT => $this->getPreviousCategory($trickCategory),
            Performance::LINK_DIRECTION_LEFT => $this->getNextCategory($trickCategory),
            Performance::LINK_DIRECTION_DOWN => $this->getOppositeCategory($trickCategory),
            Performance::LINK_DIRECTION_UP => $trickCategory,
            default => null
        };

        $this->setTopTrickCategory($topTrickCategory);
    }

    private function getPreviousCategory($category) {
        return match ($category) {
            Trick::CATEGORY_ESCAPE => Trick::CATEGORY_OPTICAL,
            Trick::CATEGORY_OPTICAL => Trick::CATEGORY_MECHANICAL,
            Trick::CATEGORY_MECHANICAL => Trick::CATEGORY_SPIRITUAL,
            Trick::CATEGORY_SPIRITUAL => Trick::CATEGORY_ESCAPE,
            default => null
        };
    }

    private function getNextCategory($category) {
        return match ($category) {
            Trick::CATEGORY_ESCAPE => Trick::CATEGORY_SPIRITUAL,
            Trick::CATEGORY_SPIRITUAL => Trick::CATEGORY_MECHANICAL,
            Trick::CATEGORY_MECHANICAL => Trick::CATEGORY_OPTICAL,
            Trick::CATEGORY_OPTICAL => Trick::CATEGORY_ESCAPE,
            default => null
        };
    }

    private function getOppositeCategory($category) {
        return match ($category) {
            Trick::CATEGORY_ESCAPE => Trick::CATEGORY_MECHANICAL,
            Trick::CATEGORY_MECHANICAL => Trick::CATEGORY_ESCAPE,
            Trick::CATEGORY_OPTICAL => Trick::CATEGORY_SPIRITUAL,
            Trick::CATEGORY_SPIRITUAL => Trick::CATEGORY_OPTICAL,
            default => null
        };
    }
    /*
    ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */    

    const SUIT_SPADES = 'spades';
    const SUIT_HEARTS = 'hearts';
    const SUIT_DIAMONDS = 'diamonds';
    const SUIT_CLUBS = 'clubs';
}
