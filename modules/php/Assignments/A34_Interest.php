<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A34_Interest extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A34_Interest';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Interest');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_TAKE_COINS;
        $this->abilityText = [
            clienttranslate('After choosing a Bank die, roll it once before setting it to X. Add the rolled amount from the supply to the ${COIN} you take.'),
            clienttranslate('This \'Take Coins\' Action costs you 1 less ${ACTION_POINT}.'),
        ];
    }
}
