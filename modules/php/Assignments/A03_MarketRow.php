<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A03_MarketRow extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A03_MarketRow';
        $this->category = Assignment::CATEGORY_PERMANENT;
        $this->name = clienttranslate('Market Row');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
