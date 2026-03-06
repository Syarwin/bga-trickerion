<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
