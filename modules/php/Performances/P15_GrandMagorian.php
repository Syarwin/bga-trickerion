<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P15_GrandMagorian extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P15_GrandMagorian';
        $this->theater = Performance::THEATER_GRAND_MAGORIAN;
        $this->name = clienttranslate('Grand Magorian');
        $this->slots = [
            "0.0" => [
                "x" => 0,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_DOWN,
                        "shard" => true
                    ]
                ]
            ],
            "0.1" => [
                "x" => 0,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => true
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => false
                    ],
                ]
            ],
            "1.1" => [
                "x" => 1,
                "y" => 1,
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
            "1.2" => [
                "x" => 1,
                "y" => 2,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => false
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => false
                    ],
                ]
            ],
            "0.2" => [
                "x" => 0,
                "y" => 2,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => false
                    ],
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 0,
            "coins" => 3,
            "shards" => 0
        ];
    }
}
