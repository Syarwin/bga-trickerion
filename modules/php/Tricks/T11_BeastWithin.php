<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T11_BeastWithin extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T11_BeastWithin';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Beast Within');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::MIRROR,
            Component::ANIMAL,
            Component::ANIMAL,
            Component::ANIMAL,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 7,
            "coins" => 3,
            "shards" => 1
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 10 Fame if you own 4 Tricks (regardless of Fame Threshold).')
        ];
    }

    public function calculateScore()
    {
        return Tricks::getFiltered($this->getPlayerId(), Tricks::LOCATION_PLAYER_ALL)
            ->count() >= 4 ? 10 : 0;
    }
}
