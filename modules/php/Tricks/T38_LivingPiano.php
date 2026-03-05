<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T38_LivingPiano extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T38_LivingPiano';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Living Piano');
        $this->componentRequirements = [
            Components::ANIMAL,
            Components::GLASS,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 3;
        $this->level = 1;
        $this->yields = [
            "fame" => 1,
            "coins" => 1,
            "shards" => 0
        ];
    }
}
