<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T39_ChineseSticks extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T39_ChineseSticks';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Chinese Sticks');
        $this->componentRequirements = [
            Components::ROPE,
            Components::ROPE,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 1,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
