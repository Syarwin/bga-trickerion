<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A11_DurableComponents extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A11_DurableComponents';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Durable Components');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_PERFORM;
        $this->abilityText = [
            clienttranslate('At the end of the \'Performance\' phase, you may return one of your performed ${TRICK_MARKER} to its Trick card instead of discarding it.'),
        ];
    }
}
