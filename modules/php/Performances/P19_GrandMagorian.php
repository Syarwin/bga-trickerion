<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P19_GrandMagorian extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P19_GrandMagorian';
        $this->theater = Performance::THEATER_GRAND_MAGORIAN;
        $this->name = clienttranslate('Grand Magorian');
        $this->slots = [
            "1.0" => [
                "x" => 1,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_DOWN,
                        "shard" => true
                    ]
                ]
            ],
            "1.1" => [
                "x" => 1,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => true
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_DOWN,
                        "shard" => false
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => false
                    ],
                ]
            ],
            "1.2" => [
                "x" => 1,
                "y" => 2,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => false
                    ],
                ]
            ],
            "0.1" => [
                "x" => 0,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => false
                    ],
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 1,
            "coins" => 0,
            "shards" => 1
        ];
    }
}
