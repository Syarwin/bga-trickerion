<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A27_HypnoticMotivation extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A27_HypnoticMotivation';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Hypnotic Motivation');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
