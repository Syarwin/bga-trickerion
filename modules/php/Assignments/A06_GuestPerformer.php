<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A06_GuestPerformer extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A06_GuestPerformer';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Guest Performer');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_SET_UP_TRICK;
        $this->abilityText = [
            clienttranslate('You may set up the ${TRICK_MARKER} even if the Performance card has no free ${TRICK_MARKER} slots. Place the ${TRICK_MARKER} on an unused area anywhere on the Performance card. This ${TRICK_MARKER} is considered part of the Performance.'),
        ];
    }
}
