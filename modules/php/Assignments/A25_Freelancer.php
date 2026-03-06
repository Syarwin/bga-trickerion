<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A25_Freelancer extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A25_Freelancer';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Freelancer');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [
            clienttranslate('You may choose to place this ${GENERIC_CHARACTER} on any other Location instead of the Workshop.'),
        ];
    }
}
