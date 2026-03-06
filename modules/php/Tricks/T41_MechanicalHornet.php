<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T41_MechanicalHornet extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T41_MechanicalHornet';
        $this->category = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Mechanical Hornet');
        $this->componentRequirements = [
            Component::COG,
            Component::PETROLEUM,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 4,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
