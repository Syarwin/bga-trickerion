<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T24_Metamorphosis extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T24_Metamorphosis';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Metamorphosis");
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::DISGUISE,
            Component::DISGUISE,
            Component::ROPE,
            Component::ROPE,
            Component::ROPE,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 10,
            "coins" => 4,
            "shards" => 0
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 1 additional Fame for every 3 unspent Coins you have (rounded down).')
        ];
    }

    public function calculateScore()
    {
        return floor($this->getPlayer()->getCoins() / 3);
    }
}
