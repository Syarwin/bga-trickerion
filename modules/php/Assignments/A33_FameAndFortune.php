<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A33_FameAndFortune extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A33_FameAndFortune';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Fame and Fortune');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
