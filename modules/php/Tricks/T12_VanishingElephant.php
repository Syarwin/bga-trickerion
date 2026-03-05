<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T12_VanishingElephant extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T12_VanishingElephant';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Vanishing Elephant');
        $this->componentRequirements = [
            Components::MIRROR,
            Components::PADDLOCK,
            Components::PADDLOCK,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 9,
            "coins" => 4,
            "shards" => 0
        ];
    }
}
