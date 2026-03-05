<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T20_GhostTrap extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T20_GhostTrap';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Ghost Trap');
        $this->componentRequirements = [
            Components::ANIMAL,
            Components::ANIMAL,
            Components::ANIMAL,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 3,
            "shards" => 1
        ];
    }
}
