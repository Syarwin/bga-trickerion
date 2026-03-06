<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A18_ReplacableParts extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A18_ReplacableParts';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Replacable Parts');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
