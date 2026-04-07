<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T12_VanishingElephant extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T12_VanishingElephant';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Vanishing Elephant');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::PADLOCK,
            Component::PADLOCK,
            Component::ANIMAL,
            Component::ANIMAL,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 9,
            "coins" => 4,
            "shards" => 0
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 7 Fame if you have a Manager.')
        ];
    }

    public function calculateScore()
    {
        return $this->getPlayer()->hasSpecialist(Character::TYPE_MANAGER) ? 7 : 0;
    }
}
