<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A08_HeadlinerTrick extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A08_HeadlinerTrick';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Headliner Trick');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_SET_UP_TRICK;
        $this->abilityText = [
            clienttranslate('If you create one or more ${LINK} as you set up the ${TRICK_MARKER}, you receive the payment for each ${LINK} twice (including ${SHARD}).'),
        ];
    }
}
