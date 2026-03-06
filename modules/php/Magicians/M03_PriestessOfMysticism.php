<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M03_PriestessOfMysticism extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M03_PriestessOfMysticism';
        $this->favoriteTrickCategory = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Priestess of Mysticism');
        $this->ability = [
            "name" => clienttranslate('Fate Weaving'),
            "effect" => clienttranslate('For 1 Dark Alley Action Point, you may discard the Active Prophecy, then replace it with any of the Pending Prophecies and draw a new one in its place.'),
        ];
    }
}