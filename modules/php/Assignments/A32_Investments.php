<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A32_Investments extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A32_Investments';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Investments');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_TAKE_COINS;
        $this->abilityText = [
            clienttranslate('You may immediately spend any amount of the received <coin> to buy up to three <component> available at the Market Row.'),
        ];
    }
}
