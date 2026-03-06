<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A09_NewTwist extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A09_NewTwist';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('New Twist');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_SET_UP_TRICK;
        $this->abilityText = [
            clienttranslate('You may set up the same ${TRICK_MARKER} on the same Performance card for a second time.'),
        ];
    }
}
