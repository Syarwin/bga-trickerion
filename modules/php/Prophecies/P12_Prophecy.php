<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P12_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P12_Prophecy';
        $this->ability = [
            clienttranslate('The \'Learn Trick\' Action costs 1 Action Point.')
        ];
    }
}
