<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A42_TravelingMerchants extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A42_TravelingMerchants';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Traveling Merchants');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
