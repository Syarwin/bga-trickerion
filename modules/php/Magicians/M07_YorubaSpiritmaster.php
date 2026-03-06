<?php

namespace Bga\Games\trickerionlegendsofillusion\Magicians;

use Bga\Games\trickerionlegendsofillusion\Models\Magician;
use Bga\Games\trickerionlegendsofillusion\Models\Trick;

class M07_YorubaSpiritmaster extends Magician
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'M07_YorubaSpiritmaster';
        $this->favoriteTrickCategory = Trick::CATEGORY_SPIRITUAL;
        $this->name = clienttranslate('Yoruba Spiritmaster');
        $this->ability = [
            "name" => clienttranslate('Soul Possession'),
            "effect" => clienttranslate('Once per turn, before an opponent chooses a card to Perform, you may pay one Shard. If you do, you may choose the card to Perform for that player. The chosen card must have at least one of the opponent\'s Trick markers on it.'),
        ];
    }
}