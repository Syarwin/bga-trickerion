<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_HIRE_CHARACTER;
        $this->abilityText = [
            clienttranslate('You immediately receive the hired <disk>. You may place an Assignment card from your hand below it and place it during this \'Place Characters\' phase.'),
        ];
    }
}
