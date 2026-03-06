<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A19_Invention extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A19_Invention';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Invention');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You receive ${FAME} equal to the total number of Advanced and Superior ${COMPONENT} required for the prepared Trick.'),
        ];
    }
}
