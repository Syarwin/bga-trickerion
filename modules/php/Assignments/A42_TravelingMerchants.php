<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_ORDER;
        $this->abilityText = [
            clienttranslate('You may pay the ${COIN} price plus 1 of the ordered ${COMPONENT} to immediately receive one from the supply.'),
            clienttranslate('You may use this for any number of \'Order\' Actions.'),
        ];
    }
}
