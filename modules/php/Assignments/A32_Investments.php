<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

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
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [];
    }
}
