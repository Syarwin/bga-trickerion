<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T34_AssistantsRevenge extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T34_AssistantsRevenge';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate("Assistant's Revenge");
        $this->componentRequirements = [
            Components::MIRROR,
            Components::MIRROR,
            Components::SAW,
            Components::SAW,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 6,
            "shards" => 0
        ];
    }
}
