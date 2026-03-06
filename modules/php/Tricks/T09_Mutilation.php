<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T09_Mutilation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T09_Mutilation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Mutilation');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::SAW,
            Component::SAW,
            Component::MIRROR,
            Component::MIRROR,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
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
