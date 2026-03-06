<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T14_BreathOfLife extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T14_BreathOfLife';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Breath of Life');
        $this->componentRequirements = [
            Component::SAW,
            Component::METAL,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
