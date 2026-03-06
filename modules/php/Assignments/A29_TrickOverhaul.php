<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A29_TrickOverhaul extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A29_TrickOverhaul';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Trick Overhaul');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
