<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T04_CardManipulation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T04_CardManipulation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Card Manipulation');
        $this->componentRequirements = [
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 1,
            "coins" => 1,
            "shards" => 1
        ];
    }
}
