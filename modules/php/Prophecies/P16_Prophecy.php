<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P16_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P16_Prophecy';
        $this->ability = [
            clienttranslate('Thursday and Sunday have no Yield modifiers.')
        ];
    }
}
