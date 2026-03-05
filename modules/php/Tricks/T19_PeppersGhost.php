<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T19_PeppersGhost extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T19_PeppersGhost';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Pepper's Ghost");
        $this->componentRequirements = [
            Components::DISGUISE,
            Components::DISGUISE,
            Components::SAW,
            Components::SAW,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 4,
            "coins" => 5,
            "shards" => 0
        ];
    }
}
