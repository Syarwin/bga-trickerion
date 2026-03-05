<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
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
            Components::MIRROR,
            Components::FABRIC,
            Components::FABRIC,
            Components::METAL,
            Components::METAL,
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
