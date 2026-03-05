<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T7_ShatteredMirror extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T7_ShatteredMirror';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Shattered Mirror');
        $this->componentRequirements = [
            Components::MIRROR,
            Components::MIRROR,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 5,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
