<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P03_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P03_Prophecy';
        $this->ability = [
            clienttranslate('Any time you receive one or more Shards, you also receive 2 Fame.')
        ];
    }
}
