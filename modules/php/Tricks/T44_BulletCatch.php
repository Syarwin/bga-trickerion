<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T44_BulletCatch extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T44_BulletCatch';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Bullet Catch');
        $this->componentRequirements = [
            Components::PETROLEUM,
            Components::PETROLEUM,
            Components::ROPE,
            Components::ROPE,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 4,
            "shards" => 0
        ];
    }
}
