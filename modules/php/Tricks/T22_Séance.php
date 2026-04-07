<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T22_Séance extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T22_Séance';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Séance");
        $this->componentRequirements = [
            Component::MIRROR,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::GLASS,
            Component::GLASS,
            Component::WOOD,
            Component::WOOD,
            Component::WOOD,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 7,
            "coins" => 5,
            "shards" => 1
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 3 Fame for each Apprentice you have.')
        ];
    }

    public function calculateScore()
    {
        return Characters::getFiltered($this->getPlayerId())
            ->where('type', Character::TYPE_APPRENTICE)
            ->whereNot('location', [Characters::LOCATION_SUPPLY, Characters::LOCATION_INCOMING])
            ->count() * 3;
    }
}
