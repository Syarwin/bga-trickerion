<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A02_Downtown extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A02_Downtown';
        $this->category = Assignment::CATEGORY_PERMANENT;
        $this->name = clienttranslate('Downtown');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
