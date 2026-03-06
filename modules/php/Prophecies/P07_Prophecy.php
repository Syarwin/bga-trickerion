<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P07_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P07_Prophecy';
        $this->ability = [
            clienttranslate('Double the amount of Coins on the chosen die when you take the \'Take Coins\' Action.')
        ];
    }
}
