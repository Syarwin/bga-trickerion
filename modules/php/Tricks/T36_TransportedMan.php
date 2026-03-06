<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T36_TransportedMan extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T36_TransportedMan';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Transported Man');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::DISGUISE,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 3,
            "shards" => 2
        ];
    }
}
