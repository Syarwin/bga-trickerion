<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P04_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P04_Prophecy';
        $this->ability = [
            clienttranslate('Component types in the Market Row\'s Order area can be bought with \'Buy\' Actions.')
        ];
    }
}
