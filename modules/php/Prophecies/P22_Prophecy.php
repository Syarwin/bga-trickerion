<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P22_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P22_Prophecy';
        $this->ability = [
            clienttranslate('Saturday has a Yield modifier: +1 Fame and +1 Coin (similar to Sunday).')
        ];
    }
}
