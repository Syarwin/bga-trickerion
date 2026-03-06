<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T20_GhostTrap extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T20_GhostTrap';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Ghost Trap');
        $this->componentRequirements = [
            Component::ANIMAL,
            Component::ANIMAL,
            Component::ANIMAL,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
            Component::WOOD,
            Component::WOOD,
            Component::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 3,
            "shards" => 1
        ];
    }
}
