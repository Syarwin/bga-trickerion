<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
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
            Components::MIRROR,
            Components::SAW,
            Components::SAW,
            Components::METAL,
            Components::METAL,
            Components::METAL,
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
