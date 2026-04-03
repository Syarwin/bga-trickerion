<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A15_DirectorsFavor extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A15_DirectorsFavor';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate("Director's Favor");
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_RESCHEDULE;
        $this->abilityText = [
            clienttranslate('Instead of moving your own <trick-marker>, you may move an opponent\'s <trick-marker>.'),
        ];
    }
}
