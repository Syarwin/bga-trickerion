<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T10_StairsOfWater extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T10_StairsOfWater';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Stairs of Water');
        $this->componentRequirements = [
            Components::MIRROR,
            Components::COG,
            Components::PETROLEUM,
            Components::PETROLEUM,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 4,
            "shards" => 2
        ];
    }
}
