<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A17_TestRun extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A17_TestRun';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Test Run');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You may choose to receive 1 less ${TRICK_MARKER} on the prepared Trick. If you do, you immediately receive the Trick\'s ${FAME}, ${COIN} and ${SHARD} Yields.'),
        ];
    }
}
