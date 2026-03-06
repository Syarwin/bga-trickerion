<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A38_BarterWithPower extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A38_BarterWithPower';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Barter with Power');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
