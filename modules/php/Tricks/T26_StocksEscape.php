<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
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
            Component::WOOD,
            Component::WOOD,
            Component::METAL,
            Component::METAL,
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
