<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T33_BuriedAlive extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T33_BuriedAlive';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Buried Alive');
        $this->componentRequirements = [
            Components::PADDLOCK,
            Components::PADDLOCK,
            Components::PADDLOCK,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 4,
            "coins" => 4,
            "shards" => 0
        ];
    }
}
