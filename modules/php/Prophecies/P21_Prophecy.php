<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P21_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P21_Prophecy';
        $this->ability = [
            clienttranslate('All Components cost 2 Coins when you buy them.')
        ];
    }
}
