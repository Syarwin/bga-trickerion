<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
