<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A45_WorkshopDelivery extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A45_WorkshopDelivery';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Workshop Delivery');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
