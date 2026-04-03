<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A35_Empower extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A35_Empower';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Empower');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_ANY;
        $this->abilityText = [
            clienttranslate('If you use \'Enhance Character\' by paying a <shard> when you place this <disk>, you receive 3 additional <action-point> instead of the usual 1.'),
        ];
    }
}
