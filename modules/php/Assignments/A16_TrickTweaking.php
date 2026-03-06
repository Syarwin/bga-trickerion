<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A16_TrickTweaking extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A16_TrickTweaking';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Trick Tweaking');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
