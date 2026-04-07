<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T33_BuriedAlive extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T33_BuriedAlive';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Buried Alive');
        $this->componentRequirements = [
            Component::PADLOCK,
            Component::PADLOCK,
            Component::PADLOCK,
            Component::WOOD,
            Component::WOOD,
            Component::WOOD,
        ];
        $this->preparationCost = 1;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 4,
            "coins" => 4,
            "shards" => 0
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 7 Fame if you have an Engineer.')
        ];
    }

    public function calculateScore()
    {
        return $this->getPlayer()->hasSpecialist(Character::TYPE_ENGINEER) ? 7 : 0;
    }
}
