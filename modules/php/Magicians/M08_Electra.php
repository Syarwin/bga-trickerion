<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M08_Electra extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M08_Electra';
        $this->favoriteTrickCategory = Trick::CATEGORY_MECHANICAL;
        $this->name = clienttranslate('Electra');
        $this->ability = [
            "name" => clienttranslate('Supercharge'),
            "effect" => clienttranslate('You may choose to pile two markers of the same Trick on a Trick slot when you set up a Trick. It counts as one Trick and Link bonuses are paid only once. When performed, this Trick Yields 1/2/3 additional Fame point(s) and Coin(s) depending on its Level (1/2/3).'),
        ];
    }
}