<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T21_BalsamosSkull extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T21_BalsamosSkull';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Balsamo's Skull");
        $this->componentRequirements = [
            Components::PADDLOCK,
            Components::ROPE,
            Components::ROPE,
            Components::METAL,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 4,
            "coins" => 3,
            "shards" => 2
        ];
    }
}
