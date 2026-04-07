<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T35_IronMaiden extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T35_IronMaiden';
        $this->category = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('Iron Maiden');
        $this->componentRequirements = [
            Component::PADLOCK,
            Component::PADLOCK,
            Component::SAW,
            Component::SAW,
            Component::SAW,
            Component::METAL,
            Component::METAL,
            Component::METAL,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 5,
            "shards" => 1
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 4 Fame for each Level 1 Trick you own, even if you don\'t meet their Component requirements.')
        ];
    }

    public function calculateScore()
    {
        return Tricks::getFiltered($this->getPlayerId(), Tricks::LOCATION_PLAYER_ALL)
            ->where('level', 1)
            ->count() * 4;
    }
}
