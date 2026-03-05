<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T37_LinkingRings extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T37_LinkingRings';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Linking Rings');
        $this->componentRequirements = [
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 0,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
