<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T11_BeastWithin extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T11_BeastWithin';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Beast Within');
        $this->componentRequirements = [
            Components::DISGUISE,
            Components::MIRROR,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 7,
            "coins" => 3,
            "shards" => 1
        ];
    }
}
