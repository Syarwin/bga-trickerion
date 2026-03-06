<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P01_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P01_Prophecy';
        $this->ability = [
            clienttranslate('Each Character has only 1 base Action Point.')
        ];
    }
}