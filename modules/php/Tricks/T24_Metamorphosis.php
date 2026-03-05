<?php

namespace Bga\Games\trickerionlegendsofillusion\Tactics;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T24_Metamorphosis extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T24_Metamorphosis';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Metamorphosis");
        $this->componentRequirements = [
            Components::DISGUISE,
            Components::DISGUISE,
            Components::DISGUISE,
            Components::ROPE,
            Components::ROPE,
            Components::ROPE,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
            Components::GLASS,
            Components::GLASS,
            Components::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 10,
            "coins" => 4,
            "shards" => 0
        ];
    }
}
