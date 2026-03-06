<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T05_SelfDecapitation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T05_SelfDecapitation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Self Decapitation');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::SAW,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 3;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
