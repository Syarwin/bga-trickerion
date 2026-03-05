<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T9_Mutilation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T9_Mutilation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Mutilation');
        $this->componentRequirements = [
            Components::DISGUISE,
            Components::SAW,
            Components::SAW,
            Components::MIRROR,
            Components::MIRROR,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 5,
            "shards" => 0
        ];
    }
}
