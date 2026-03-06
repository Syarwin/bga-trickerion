<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A31_LeakedBlueprints extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A31_LeakedBlueprints';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Leaked Blueprints');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
