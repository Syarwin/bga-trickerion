<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_HIRE_CHARACTER;
        $this->abilityText = [
            clienttranslate('You may hire any kind of ${GENERIC_CHARACTER} regardless of the die roll (as long as it\'s not an X).'),
            clienttranslate('This \'Hire Character\' Action costs you 1 less ${ACTION_POINT}.'),
        ];
    }
}
