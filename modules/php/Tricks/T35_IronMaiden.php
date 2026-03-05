<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T35_IronMaiden extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T35_IronMaiden';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Iron Maiden');
        $this->componentRequirements = [
            Components::PADDLOCK,
            Components::PADDLOCK,
            Components::SAW,
            Components::SAW,
            Components::SAW,
            Components::METAL,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 5,
            "shards" => 1
        ];
    }
}
