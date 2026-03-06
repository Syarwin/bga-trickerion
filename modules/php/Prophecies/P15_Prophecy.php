<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P15_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P15_Prophecy';
        $this->ability = [
            clienttranslate('If you Advertised this turn, your Apprentices gain +1 base Action Point.')
        ];
    }
}
