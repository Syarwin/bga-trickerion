<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A13_GrandPremiere extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A13_GrandPremiere';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Grand Premiere');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_PERFORM;
        $this->abilityText = [
            clienttranslate('If one of your own <trick-marker> in your Performance is performed for the first time in the game, double that Trick\'s <coin>, <fame>, or <shard> Yield for this Performance (choose one).'),
        ];
    }
}
