<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T10_StairsOfWater extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T10_StairsOfWater';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Stairs of Water');
        $this->componentRequirements = [
            Component::MIRROR,
            Component::COG,
            Component::PETROLEUM,
            Component::PETROLEUM,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 5,
            "coins" => 4,
            "shards" => 2
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 2 Fame for each Advanced Component in your Workshop (including the Manager\'s bonus Components).')
        ];
    }

    public function calculateScore()
    {
        return Components::getFiltered($this->getPlayerId())
            ->where('cost', 2)
            ->reduce(function ($sum, $component) {
                return $sum + $component->getEffectiveCount();
            }, 0) * 2;        
    }
}
