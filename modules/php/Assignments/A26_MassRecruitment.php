<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_HIRE_CHARACTER;
        $this->abilityText = [
            clienttranslate('If you hire an Apprentice, you may pay 3 <coin> to hire a second one. This costs no additional <action-point> and is independent from the die rolls.'),
        ];
    }
}
