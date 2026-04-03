<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A36_ComponentSale extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A36_ComponentSale';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Component Sale');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('As one \'Buy\' Action, you may buy up to 4 <component> available at the Market Row, in any combination.'),
        ];
    }
}
