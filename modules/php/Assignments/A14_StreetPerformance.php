<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A14_StreetPerformance extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A14_StreetPerformance';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Street Performance');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
