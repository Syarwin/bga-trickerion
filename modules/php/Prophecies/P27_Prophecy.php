<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P27_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P27_Prophecy';
        $this->ability = [
            clienttranslate('You must place your Characters on the Slot with the lowest available Action Point modifier.')
        ];
    }
}
