<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T5_SelfDecapitation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T5_SelfDecapitation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Self Decapitation');
        $this->componentRequirements = [
            Components::DISGUISE,
            Components::SAW,
            Components::METAL,
            Components::METAL,
            Components::METAL,
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
