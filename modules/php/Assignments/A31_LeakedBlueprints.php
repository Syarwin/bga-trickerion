<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A31_LeakedBlueprints extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A31_LeakedBlueprints';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Leaked Blueprints');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_LEARN_TRICK;
        $this->abilityText = [
            clienttranslate('If the learned Trick has ${FAME_THRESHOLD} 16 or less, you receive it with 1 ${TRICK_MARKER}, even if you don\'t meet its ${COMPONENT} requirements.'),
        ];
    }
}
