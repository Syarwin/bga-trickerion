<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_ORDER;
        $this->abilityText = [
            clienttranslate('Place the ordered <component> next to the Market Row\'s Buy area instead of the Order area. It counts as part of the Market Row\'s stock this turn. Return this <component> to the supply during the \'Orders Arrive\' phase.'),
        ];
    }
}
