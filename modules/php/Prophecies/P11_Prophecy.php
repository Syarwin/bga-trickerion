<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P11_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P11_Prophecy';
        $this->ability = [
            clienttranslate('Friday has a Yield modifier: -1 Fame and -1 Coin (similar to Thursday).')
        ];
    }
}
