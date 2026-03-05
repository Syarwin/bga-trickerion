<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T42_SawingTheAssistantInHalf extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T42_SawingTheAssistantInHalf';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Sawing the Assistant in Half');
        $this->componentRequirements = [
            Components::SAW,
            Components::SAW,
            Components::SAW,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 1,
            "coins" => 5,
            "shards" => 0
        ];
    }
}
