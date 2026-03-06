<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T35_IronMaiden extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T35_IronMaiden';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Iron Maiden');
        $this->componentRequirements = [
            Component::PADDLOCK,
            Component::PADDLOCK,
            Component::SAW,
            Component::SAW,
            Component::SAW,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 5,
            "shards" => 1
        ];
    }
}
