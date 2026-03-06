<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A24_HiddenTalent extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A24_HiddenTalent';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Hidden Talent');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
