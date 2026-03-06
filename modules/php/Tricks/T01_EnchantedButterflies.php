<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T01_EnchantedButterflies extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T01_EnchantedButterflies';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Enchanted Butterflies');
        $this->componentRequirements = [
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 2,
            "coins" => 0,
            "shards" => 0
        ];
    }
}