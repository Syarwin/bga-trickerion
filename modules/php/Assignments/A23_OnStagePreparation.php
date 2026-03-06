<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A23_OnStagePreparation extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A23_OnStagePreparation';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('On-stage Preparation');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
