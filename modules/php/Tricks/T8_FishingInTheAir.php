<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T8_FishingInTheAir extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T8_FishingInTheAir';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Fishing in the Air');
        $this->componentRequirements = [
            Components::ROPE,
            Components::ROPE,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 4,
            "shards" => 1
        ];
    }
}
