<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P09_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P09_Prophecy';
        $this->ability = [
            clienttranslate('You may place two Characters on each of your turns during the \'Place Characters\' phase.')
        ];
    }
}
