<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T46_HorrorSaws extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T46_HorrorSaws';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Horror Saws');
        $this->componentRequirements = [
            Components::COG,
            Components::COG,
            Components::SAW,
            Components::SAW,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 4,
            "coins" => 8,
            "shards" => 0
        ];
    }
}
