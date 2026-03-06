<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A09_NewTwist extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A09_NewTwist';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('New Twist');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
