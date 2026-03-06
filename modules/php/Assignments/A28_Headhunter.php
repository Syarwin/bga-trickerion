<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A28_Headhunter extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A28_Headhunter';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Headhunter');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
