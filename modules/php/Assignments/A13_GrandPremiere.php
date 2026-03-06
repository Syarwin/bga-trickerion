<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
