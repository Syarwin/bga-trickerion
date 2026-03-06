<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_BUY;
        $this->abilityText = [
            clienttranslate('You receive ${FAME} equal to the amount of ${COIN} you paid for this \'Buy\' Action minus 1.'),
        ];
    }
}
