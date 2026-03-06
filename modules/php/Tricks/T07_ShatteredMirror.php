<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T07_ShatteredMirror extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T07_ShatteredMirror';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Shattered Mirror');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::MIRROR,
            Component::WOOD,
            Component::WOOD,
            Component::WOOD,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 5,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
