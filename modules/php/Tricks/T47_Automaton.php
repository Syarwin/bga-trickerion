<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T47_Automaton extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T47_Automaton';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Automaton');
        $this->componentRequirements = [
            Component::COG,
            Component::COG,
            Component::COG,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 3;
        $this->yields = [
            "fame" => 7,
            "coins" => 7,
            "shards" => 0
        ];
    }
}
