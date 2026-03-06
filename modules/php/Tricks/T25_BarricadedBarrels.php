<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
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
            Component::WOOD,
            Component::WOOD,
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
