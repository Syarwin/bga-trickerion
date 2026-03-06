<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A33_FameAndFortune extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A33_FameAndFortune';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Fame and Fortune');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_TAKE_COINS;
        $this->abilityText = [
            clienttranslate('You also gain ${FAME} equal to the amount of ${COIN} on the chosen die minus 1.'),
        ];
    }
}
