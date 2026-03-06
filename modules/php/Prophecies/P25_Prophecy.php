<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P25_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P25_Prophecy';
        $this->ability = [
            clienttranslate('The \'Hire Character\' Action costs 1 Action Point.')
        ];
    }
}
