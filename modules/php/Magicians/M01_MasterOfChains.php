<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M01_MasterOfChains extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M01_MasterOfChains';
        $this->favoriteTrickCategory = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Master of Chains');
        $this->ability = [
            "name" => clienttranslate('Break Free'),
            "effect" => clienttranslate('Before the \'Performance\' phase, you may take a special \'Reschedule\' Action without placing any Characters. Unlike the normal \'Reschedule\' Action, you receive the Link bonuses if you create Link(s) with this placement.'),
        ];
    }
}