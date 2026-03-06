<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A08_HeadlinerTrick extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A08_HeadlinerTrick';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Headliner Trick');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
