<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A34_Interest extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A34_Interest';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Interest');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
