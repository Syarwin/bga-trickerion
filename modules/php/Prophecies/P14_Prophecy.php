<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P14_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P14_Prophecy';
        $this->ability = [
            clienttranslate('During the \'Performance\' phase, all Coin Yields are received as Fame.')
        ];
    }
}
