<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T31_Walled extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T31_Walled';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Walled');
        $this->componentRequirements = [
            Component::PADLOCK,
            Component::WOOD,
            Component::WOOD,
            Component::WOOD,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 2,
            "shards" => 1
        ];
    }
}
