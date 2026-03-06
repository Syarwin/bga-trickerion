<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A30_ThirstForKnowledge extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A30_ThirstForKnowledge';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Thirst for Knowledge');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_LEARN_TRICK;
        $this->abilityText = [
            clienttranslate('You may learn a Trick from any category regardless of the die roll (as long as it\'s not an X).'),
            clienttranslate('This \'Learn Trick\' Action costs you 1 less ${ACTION_POINT}.'),
        ];
    }
}
