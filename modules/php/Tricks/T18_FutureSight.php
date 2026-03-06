<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T18_FutureSight extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T18_FutureSight';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Future Sight');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::FABRIC,
            Component::FABRIC,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 1;
        $this->level = 2;
        $this->yields = [
            "fame" => 4,
            "coins" => 1,
            "shards" => 0
        ];
    }
}
