<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P20_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P20_Prophecy';
        $this->ability = [
            clienttranslate('Assignment cards are not revealed after the \'Assignment\' phase. Players reveal them one by one right before placing the Character above them.')
        ];
    }
}
