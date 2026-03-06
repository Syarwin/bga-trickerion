<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T13_MindReading extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T13_MindReading';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Mind Reading');
        $this->componentRequirements = [
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 1;
        $this->slots = 3;
        $this->level = 1;
        $this->yields = [
            "fame" => 0,
            "coins" => 0,
            "shards" => 1
        ];
    }
}
