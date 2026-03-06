<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T29_PrisonBreak extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T29_PrisonBreak';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Prison Break');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::DISGUISE,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 2;
        $this->yields = [
            "fame" => 3,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
