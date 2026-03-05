<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T28_WaterTankEscape extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T28_WaterTankEscape';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Water Tank Escape');
        $this->componentRequirements = [
            Components::ROPE,
            Components::GLASS,
            Components::GLASS,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 1,
            "coins" => 2,
            "shards" => 1
        ];
    }
}
