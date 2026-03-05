<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T32_WolfCage extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T32_WolfCage';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Wolf Cage');
        $this->componentRequirements = [
            Components::PETROLEUM,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 3,
            "shards" => 1
        ];
    }
}
