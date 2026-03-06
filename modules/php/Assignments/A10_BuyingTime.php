<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
