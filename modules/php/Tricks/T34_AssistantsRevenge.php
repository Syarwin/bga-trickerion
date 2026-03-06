<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
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
            Component::MIRROR,
            Component::MIRROR,
            Component::SAW,
            Component::SAW,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
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
