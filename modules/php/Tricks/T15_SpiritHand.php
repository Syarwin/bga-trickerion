<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T15_SpiritHand extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T15_SpiritHand';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Spirit Hand');
        $this->componentRequirements = [
            Component::ANIMAL,
            Component::ROPE,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 1,
            "shards" => 1
        ];
    }
}
