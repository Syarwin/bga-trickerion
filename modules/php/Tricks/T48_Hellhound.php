<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T48_Hellhound extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T48_Hellhound';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Hellhound');
        $this->componentRequirements = [
            Component::PADLOCK,
            Component::PADLOCK,
            Component::PETROLEUM,
            Component::ANIMAL,
            Component::ANIMAL,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 5,
            "shards" => 1
        ];
    }
}
