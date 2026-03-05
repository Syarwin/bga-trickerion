<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T03_PubInABottle extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T03_PubInABottle';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Pub-In-A-Bottle');
        $this->componentRequirements = [
            Components::ROPE,
            Components::SAW,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
