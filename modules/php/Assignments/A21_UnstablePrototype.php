<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A21_UnstablePrototype extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A21_UnstablePrototype';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Unstable Prototype');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
