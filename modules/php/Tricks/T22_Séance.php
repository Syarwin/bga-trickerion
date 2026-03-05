<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T22_Séance extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T22_Séance';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Séance");
        $this->componentRequirements = [
            Components::MIRROR,
            Components::PETROLEUM,
            Components::PETROLEUM,
            Components::GLASS,
            Components::GLASS,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 7,
            "coins" => 5,
            "shards" => 1
        ];
    }
}
