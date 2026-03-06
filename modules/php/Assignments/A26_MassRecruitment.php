<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A26_MassRecruitment extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A26_MassRecruitment';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Mass Recruitment');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
