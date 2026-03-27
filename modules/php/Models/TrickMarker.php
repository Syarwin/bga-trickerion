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
        'upTrickType' => ['trick_marker_up_trick_type', 'string'],
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
