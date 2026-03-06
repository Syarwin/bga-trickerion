<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A14_StreetPerformance extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A14_StreetPerformance';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Street Performance');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_RESCHEDULE;
        $this->abilityText = [
            clienttranslate('Instead of moving the ${TRICK_MARKER}, you may remove it from the Performance card and immediately receive that Trick\'s Yields. Yield modifiers apply.'),
        ];
    }
}
