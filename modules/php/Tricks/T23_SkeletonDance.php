<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T23_SkeletonDance extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T23_SkeletonDance';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Skeleton Dance");
        $this->componentRequirements = [
            Components::COG,
            Components::COG,
            Components::ROPE,
            Components::ROPE,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 4,
            "shards" => 0
        ];
    }
}
