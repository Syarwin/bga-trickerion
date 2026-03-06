<?php

namespace Bga\Games\trickerionlegendsofillusion\Prophecies;

use Bga\Games\trickerionlegendsofillusion\Models\Prophecy;

class P02_Prophecy extends Prophecy
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P02_Prophecy';
        $this->ability = [
            clienttranslate('Any time you receive one or more Shards, you receive an additional one.')
        ];
    }
}
