<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A11_DurableComponents extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A11_DurableComponents';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Durable Components');
        $this->boardLocation = Assignment::BOARD_LOCATION_THEATER;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
