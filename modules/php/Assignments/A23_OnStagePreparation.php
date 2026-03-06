<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A23_OnStagePreparation extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A23_OnStagePreparation';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('On-stage Preparation');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('After preparing the Trick, you may immediately move one of the received ${TRICK_MARKER} onto an empty slot on one of the Performance cards. You don\'t receive ${LINK} payment if you created a ${LINK} this way.'),
        ];
    }
}
