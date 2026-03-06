<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A04_Workshop extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A04_Workshop';
        $this->category = Assignment::CATEGORY_PERMANENT;
        $this->name = clienttranslate('Workshop');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
