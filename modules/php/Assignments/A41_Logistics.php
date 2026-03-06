<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A41_Logistics extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A41_Logistics';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Logistics');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
