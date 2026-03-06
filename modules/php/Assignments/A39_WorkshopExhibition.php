<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A39_WorkshopExhibition extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A39_WorkshopExhibition';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Workshop Exhibition');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
