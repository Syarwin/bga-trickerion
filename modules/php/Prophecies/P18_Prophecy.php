<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P18_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P18_Prophecy';
        $this->ability = [
            clienttranslate('You have to pay 3 Coins to use the +2 Action Point Slots at the Downtown, Market Row and Dark Alley.')
        ];
    }
}
