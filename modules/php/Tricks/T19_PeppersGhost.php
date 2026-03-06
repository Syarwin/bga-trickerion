<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
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
            Component::DISGUISE,
            Component::DISGUISE,
            Component::SAW,
            Component::SAW,
            Component::GLASS,
            Component::GLASS,
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
