<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A18_ReplacableParts extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A18_ReplacableParts';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Replacable Parts');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You may choose to receive 1 less ${TRICK_MARKER} on the prepared Trick. If you do, you may put one ${TRICK_MARKER} on another Trick you own with the same or lower ${FAME_THRESHOLD} (even if you don\'t meet its ${COMPONENT} requirements).'),
        ];
    }
}
