<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T06_PaperShred extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T06_PaperShred';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Paper Shred');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::SAW,
            Component::SAW,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 4,
            "coins" => 2,
            "shards" => 1
        ];
    }
}
