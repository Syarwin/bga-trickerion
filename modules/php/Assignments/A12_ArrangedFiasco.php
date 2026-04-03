<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A12_ArrangedFiasco extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A12_ArrangedFiasco';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Arranged Fiasco');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_PERFORM;
        $this->abilityText = [
            clienttranslate('When you <perform>, you receive +1 <fame> for each of your own <trick-marker>, while other players receive -1 <fame> for each of their <trick-marker>.'),
        ];
    }
}
