<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P24_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P24_Prophecy';
        $this->ability = [
            clienttranslate('The \'Take Coins\' Action costs 1 Action Point.')
        ];
    }
}
