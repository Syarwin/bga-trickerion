<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A22_EnhanceTrick extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A22_EnhanceTrick';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Enhance Trick');
        $this->boardLocation = Assignment::BOARD_LOCATION_WORKSHOP;
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You may place one of your <shard> on the prepared Trick. It is considered spent. That Trick\'s <fame> and <coin> Yields are increased by 1 for the remainder of the game.'),
        ];
    }
}
