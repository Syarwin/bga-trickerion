<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A10_BuyingTime extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A10_BuyingTime';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Buying Time');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_SET_UP_TRICK;
        $this->abilityText = [
            clienttranslate('After setting up the <trick-marker>, you may switch the position of two Performance cards and receive 3 <coin>.'),
        ];
    }
}
