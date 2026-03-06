<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A22_EnhanceTrick extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A22_EnhanceTrick';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Enhance Trick');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
