<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T17_FloatingTable extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T17_FloatingTable';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Floating Table');
        $this->componentRequirements = [
            Components::PETROLEUM,
            Components::ROPE,
            Components::ROPE,
            Components::ROPE,
            Components::FABRIC,
            Components::FABRIC,
            Components::FABRIC,
            Components::WOOD,
            Components::WOOD,
            Components::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 5,
            "coins" => 2,
            "shards" => 1
        ];
    }
}
