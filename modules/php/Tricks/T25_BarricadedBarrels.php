<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T25_BarricadedBarrels extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T25_BarricadedBarrels';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Barricaded Barrels');
        $this->componentRequirements = [
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 1,
            "coins" => 1,
            "shards" => 0
        ];
    }
}
