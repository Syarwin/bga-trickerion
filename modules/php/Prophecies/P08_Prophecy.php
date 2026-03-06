<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P08_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P08_Prophecy';
        $this->ability = [
            clienttranslate('You receive 2 Action Points instead of 1 when you use \'Enhance Character\'.')
        ];
    }
}
