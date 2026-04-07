<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;
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
        $this->scoringDescription = [
            clienttranslate('Receive 4 Fame for each Level 3 Trick you own (including this one), even if you don\'t meet the other Tricks\' Component requirements.')
        ];
    }


    public function calculateScore()
    {
        return Tricks::getFiltered($this->getPlayerId(), Tricks::LOCATION_PLAYER_ALL)
            ->where('level', 3)
            ->count() * 4;
    }
}
