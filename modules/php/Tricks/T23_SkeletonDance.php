<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T23_SkeletonDance extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T23_SkeletonDance';
        $this->category = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate("Skeleton Dance");
        $this->componentRequirements = [
            Component::COG,
            Component::COG,
            Component::ROPE,
            Component::ROPE,
            Component::GLASS,
            Component::GLASS,
            Component::GLASS,
        ];
        $this->preparationCost = 3;
        $this->slots = 3;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 4,
            "shards" => 0
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 1 Fame for each Basic Component in your Workshop (including the Manager\'s bonus Components).')
        ];
    }

    public function calculateScore()
    {
        return Components::getFiltered($this->getPlayerId())
            ->where('cost', 1)
            ->reduce(function ($sum, $component) {
                return $sum + $component->getEffectiveCount();
            }, 0);        
    }
}
