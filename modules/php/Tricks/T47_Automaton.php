<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
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
            Components::COG,
            Components::COG,
            Components::COG,
            Components::PETROLEUM,
            Components::PETROLEUM,
            Components::PETROLEUM,
            Components::METAL,
            Components::METAL,
            Components::METAL,
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
