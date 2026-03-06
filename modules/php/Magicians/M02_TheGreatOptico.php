<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M02_TheGreatOptico extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M02_TheGreatOptico';
        $this->favoriteTrickCategory = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('The Great Optico');
        $this->ability = [
            "name" => clienttranslate('Mimesis'),
            "effect" => clienttranslate('Once per turn, you may use one of your Permanent Assignment cards with an effect of an opponent\'s revealed Special Assignment card of the same Location. You may use either the copied card\'s printed ability or gain its +1 Action Point bonus instead.'),
        ];
    }
}