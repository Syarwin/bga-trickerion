<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('As one \'Buy\' Action, you may return a ${SHARD} to the supply and receive any number of ${COMPONENT} available at the Market Row for a total ${COIN} value of 4 or less without paying any ${COIN}.'),
        ];
    }
}
