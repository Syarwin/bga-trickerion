<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P05_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P05_Prophecy';
        $this->ability = [
            clienttranslate('The \'Draw Further Cards\' Action costs 1 Action Point.')
        ];
    }
}
