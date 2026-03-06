<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M04_TheMechaniker extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M04_TheMechaniker';
        $this->favoriteTrickCategory = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('The Mechaniker');
        $this->ability = [
            "name" => clienttranslate('Mechanical Enhancement'),
            "effect" => clienttranslate('Once per turn, one of your Apprentices receives an additional Action Point when placed at any Location except for the Theater.'),
        ];
    }
}