<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A07_OrchestralInterlude extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A07_OrchestralInterlude';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Orchestral Interlude');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
