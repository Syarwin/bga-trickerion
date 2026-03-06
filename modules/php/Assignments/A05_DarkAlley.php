<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A05_DarkAlley extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A05_DarkAlley';
        $this->category = Assignment::CATEGORY_PERMANENT;
        $this->name = clienttranslate('Dark Alley');
        $this->boardLocation = Assignment::BOARD_LOCATION_DARK_ALLEY;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
