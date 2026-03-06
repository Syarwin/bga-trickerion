<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T40_Levitation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T40_Levitation';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Levitation');
        $this->componentRequirements = [
            Component::ROPE,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
