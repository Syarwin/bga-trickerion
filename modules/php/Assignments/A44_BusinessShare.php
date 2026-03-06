<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A44_BusinessShare extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A44_BusinessShare';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Business Share');
        $this->boardLocation = Assignment::BOARD_LOCATION_MARKET_ROW;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [
            clienttranslate('Whenever an opponent spends ${COIN} at the Market Row this turn after you placed this ${GENERIC_CHARACTER}, those ${COIN} are paid to you.'),
        ];
    }
}
