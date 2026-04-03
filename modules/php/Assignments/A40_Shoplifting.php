<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A40_Shoplifting extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A40_Shoplifting';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Shoplifting');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('You may take one of the bought <component> directly from the Market Row instead of the supply, without paying its <coin> price. The <component> is removed from the Market Row\'s stock and replaced with a Basic <component> of your choice.'),
        ];
    }
}
