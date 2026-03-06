<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M05_TheRedLotus extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M05_TheRedLotus';
        $this->favoriteTrickCategory = Trick::CATEGORY_ESCAPE;
        $this->name = clienttranslate('The Red Lotus');
        $this->ability = [
            "name" => clienttranslate('Trick Steal'),
            "effect" => clienttranslate('You may choose to receive the Yield of an opponent\'s Trick in the same Performance instead of your own Trick\'s Yield, as long as you are the Performer and the opponent\'s Trick has the same or lower Fame Threshold as yours. The opponent receives one less Fame for the Trick stolen this way.'),
        ];
    }
}