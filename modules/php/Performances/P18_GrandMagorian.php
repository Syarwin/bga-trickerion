<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P18_GrandMagorian extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P18_GrandMagorian';
        $this->theater = Performance::THEATER_GRAND_MAGORIAN;
        $this->name = clienttranslate('Grand Magorian');
        $this->slots = [
            "0.0" => [
                "x" => 0,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => false
                    ]
                ]
            ],
            "1.0" => [
                "x" => 1,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => false
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_DOWN,
                        "shard" => false
                    ],
                ]
            ],
            "1.1" => [
                "x" => 1,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => false
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => true
                    ],
                ]
            ],
            "0.1" => [
                "x" => 0,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => true
                    ],
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 2,
            "coins" => 1,
            "shards" => 0
        ];
    }
}
