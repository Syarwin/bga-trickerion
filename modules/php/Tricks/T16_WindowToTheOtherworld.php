<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T16_WindowToTheOtherworld extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T16_WindowToTheOtherworld';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Window to the Otherworld');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::PETROLEUM,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 1;
        $this->yields = [
            "fame" => 3,
            "coins" => 2,
            "shards" => 0
        ];
    }
}
