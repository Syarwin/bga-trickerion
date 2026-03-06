<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A43_DubiousSources extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A43_DubiousSources';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Dubious Sources');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_QUICK_ORDER;
        $this->abilityText = [
            clienttranslate('During the \'End Turn\' phase, put the ${COMPONENT} on the Quick Order slot into your Workshop instead of returning it to the supply.'),
        ];
    }
}
