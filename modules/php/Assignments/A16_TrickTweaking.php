<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A16_TrickTweaking extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A16_TrickTweaking';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Trick Tweaking');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You receive +1 ${TRICK_MARKER} on the prepared Trick.'),
        ];
    }
}
