<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
