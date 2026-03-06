<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T02_RabbitFromTheTopHat extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T02_RabbitFromTheTopHat';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Rabbit from the Top Hat');
        $this->componentRequirements = [
            Component::ANIMAL,
            Component::FABRIC,
            Component::METAL,
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
