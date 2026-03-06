<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P26_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P26_Prophecy';
        $this->ability = [
            clienttranslate('You may use \'Enhance Character\' in the Theater.')
        ];
    }
}
