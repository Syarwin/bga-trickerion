<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T2_RabbitFromTheTopHat extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T2_RabbitFromTheTopHat';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Rabbit from the Top Hat');
        $this->componentRequirements = [
            Components::ANIMAL,
            Components::FABRIC,
            Components::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 3,
            "coins" => 1,
            "shards" => 0
        ];
    }
}
