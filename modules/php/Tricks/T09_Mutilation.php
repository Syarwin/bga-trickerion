<?php

namespace Bga\Games\trickerionlegendsofillusion\Tricks;

use Bga\Games\trickerionlegendsofillusion\Models\Character;
use Bga\Games\trickerionlegendsofillusion\Models\Component;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class T09_Mutilation extends Trick
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'T09_Mutilation';
        $this->category = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('Mutilation');
        $this->componentRequirements = [
            Component::DISGUISE,
            Component::SAW,
            Component::SAW,
            Component::MIRROR,
            Component::MIRROR,
            Component::FABRIC,
            Component::FABRIC,
            Component::FABRIC,
        ];
        $this->preparationCost = 2;
        $this->slots = 2;
        $this->level = 3;
        $this->yields = [
            "fame" => 6,
            "coins" => 5,
            "shards" => 0
        ];
        $this->scoringDescription = [
            clienttranslate('Receive 12 Fame if you have an Assistant, an Engineer and a Manager.')
        ];
    }

    public function calculateScore()
    {
        $hasAssistant = $this->getPlayer()->hasSpecialist(Character::TYPE_ASSISTANT);
        $hasEngineer = $this->getPlayer()->hasSpecialist(Character::TYPE_ENGINEER);
        $hasManager = $this->getPlayer()->hasSpecialist(Character::TYPE_MANAGER);

        return ($hasAssistant && $hasEngineer && $hasManager) ? 12 : 0;
    }
}
