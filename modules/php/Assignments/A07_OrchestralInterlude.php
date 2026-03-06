<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A07_OrchestralInterlude extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A07_OrchestralInterlude';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Orchestral Interlude');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_SET_UP_TRICK;
        $this->abilityText = [
            clienttranslate('You receive your Trick\'s ${LINK} payment for each ${TRICK_MARKER} connecting to the ${TRICK_MARKER} you set up (even if the category symbols don\'t match).'),
        ];
    }
}
