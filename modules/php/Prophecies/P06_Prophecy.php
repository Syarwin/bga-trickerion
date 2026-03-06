<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P06_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P06_Prophecy';
        $this->ability = [
            clienttranslate('All Performer Bonuses are doubled this turn.')
        ];
    }
}
