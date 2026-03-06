<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A06_GuestPerformer extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A06_GuestPerformer';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Guest Performer');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
