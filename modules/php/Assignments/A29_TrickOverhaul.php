<?php

namespace Bga\Games\trickerionlegendsofillusion\Assignments;

use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class A29_TrickOverhaul extends Assignment
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'A29_TrickOverhaul';
        $this->category = Assignment::CATEGORY_SPECIAL;
        $this->name = clienttranslate('Trick Overhaul');
        $this->boardLocation = Assignment::BOARD_LOCATION_DOWNTOWN;
        $this->targetAction = Assignment::TARGET_ACTION_LEARN_TRICK;
        $this->abilityText = [
            clienttranslate('As you learn this Trick, you may choose to return another Trick to the Dahlgaard Residence. If you do, place all <trick-marker> and the Symbol Marker from the returned Trick on the new Trick. You can only do this if you meet the new Trick\'s <component> requirements.'),
        ];
    }
}
