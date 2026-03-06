<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('For this \'Buy\' Action, ${COMPONENT} types you have in your Workshop count as part of the Market Row\'s stock and their ${COIN} price is reduced by 1.'),
        ];
    }
}
