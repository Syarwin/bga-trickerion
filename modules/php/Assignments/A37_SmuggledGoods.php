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
            clienttranslate('You may lose <fame> equal to the total <coin> price of the bought <component> minus 1 instead of paying <coin> for them.'),
        ];
    }
}
