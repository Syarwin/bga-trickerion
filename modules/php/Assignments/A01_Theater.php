<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A01_Theater extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A01_Theater';
        $this->category = Assignment::CATEGORY_PERMANENT;
        $this->name = clienttranslate('Theater');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}