<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T45_AztecLady extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T45_AztecLady';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Aztec Lady');
        $this->componentRequirements = [
            Component::PADDLOCK,
            Component::COG,
            Component::COG,
            Component::SAW,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 5,
            "shards" => 0
        ];
    }
}
