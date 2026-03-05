<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T43_VanishingBirdCage extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T43_VanishingBirdCage';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Vanishing Bird Cage');
        $this->componentRequirements = [
            Components::COG,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::FABRIC,
            Components::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 2,
            "coins" => 3,
            "shards" => 1
        ];
    }
}
