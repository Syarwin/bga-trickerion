<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
