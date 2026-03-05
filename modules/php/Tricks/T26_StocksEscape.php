<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T26_StocksEscape extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T26_StocksEscape';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Stocks Escape');
        $this->componentRequirements = [
            Components::WOOD,
            Components::WOOD,
            Components::METAL,
            Components::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 0,
            "coins" => 1,
            "shards" => 1
        ];
    }
}
