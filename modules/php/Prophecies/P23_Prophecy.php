<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P23_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P23_Prophecy';
        $this->ability = [
            clienttranslate('You immediately receive 2 Fame when you set up a Spiritual Trick.')
        ];
    }
}
