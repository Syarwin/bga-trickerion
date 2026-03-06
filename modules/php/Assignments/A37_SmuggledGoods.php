<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A37_SmuggledGoods extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A37_SmuggledGoods';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Smuggled Goods');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('You may lose ${FAME} equal to the total ${COIN} price of the bought ${COMPONENT} minus 1 instead of paying ${COIN} for them.'),
        ];
    }
}
