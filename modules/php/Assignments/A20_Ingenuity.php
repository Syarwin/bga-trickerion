<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A20_Ingenuity extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A20_Ingenuity';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Ingenuity');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You may \'Prepare\' the Trick even if you don\'t have enough of one ${COMPONENT} type required for it. You only get 1 ${TRICK_MARKER} if you \'Prepare\' a Trick this way.'),
        ];
    }
}
