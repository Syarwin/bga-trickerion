<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T27_BurningMummy extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T27_BurningMummy';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Burning Mummy');
        $this->componentRequirements = [
            Component::PETROLEUM,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
