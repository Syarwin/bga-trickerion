<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P19_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P19_Prophecy';
        $this->ability = [
            clienttranslate('During the \'Performance\' phase, the performing player\'s Yield modifier applies to all other players in the Performance (instead of their own).')
        ];
    }
}
