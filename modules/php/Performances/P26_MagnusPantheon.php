<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P26_MagnusPantheon extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P26_MagnusPantheon';
        $this->theater = Performance::THEATER_MAGNUS_PANTHEON;
        $this->name = clienttranslate('The Magnus Pantheon');
        $this->slots = [
            "1.0" => [
                "x" => 1,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => true
                    ],
                ]
            ],
            "0.0" => [
                "x" => 0,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_RIGHT,
                        "shard" => true
                    ],
                    [
                        "direction" => self::LINK_DIRECTION_DOWN,
                        "shard" => false
                    ],
                ]
            ],
            "0.1" => [
                "x" => 0,
                "y" => 1,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_UP,
                        "shard" => false
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
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 2,
            "coins" => 0,
            "shards" => 1
        ];
    }
}
