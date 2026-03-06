<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M06_TheGentleman extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M06_TheGentleman';
        $this->favoriteTrickCategory = Trick::CATEGORY_OPTICAL;
        $this->name = clienttranslate('The Gentleman');
        $this->ability = [
            "name" => clienttranslate('Magic for the Masses'),
            "effect" => clienttranslate('Whenever the Magician is placed on a Downtown, Market Row or Dark Alley slot, you receive Fame equal to the number of Trick cards you have.'),
        ];
    }
}