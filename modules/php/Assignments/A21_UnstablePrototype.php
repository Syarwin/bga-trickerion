<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

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
        $this->targetAction = Assignment::TARGET_ACTION_PREPARE;
        $this->abilityText = [
            clienttranslate('You may return up to 3 ${COMPONENT} required for the prepared Trick to the supply. If you do, you receive:'),
            clienttranslate('${BULLET} +1 ${TRICK_MARKER} on the Trick.'),
            clienttranslate('${BULLET} 1 ${FAME} and 1 ${COIN} for each ${COMPONENT} returned.'),
        ];
    }
}
