<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A24_HiddenTalent extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A24_HiddenTalent';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Hidden Talent');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('If you used an Apprentice to \'Prepare\' the Trick, you may return it to the supply during the \'Return Characters\' phase and immediately hire a Specialist. You have to pay Wages for that Specialist this turn.'),
        ];
    }
}
