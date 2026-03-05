<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T30_ZigZagLady extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T30_ZigZagLady';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Zig Zag Lady');
        $this->componentRequirements = [
            Components::PETROLEUM,
            Components::FABRIC,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 2,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
