<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P10_RiversideTheater extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P10_RiversideTheater';
        $this->theater = Performance::THEATER_RIVERSIDE;
        $this->name = clienttranslate('Riverside Theater');
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
                        "shard" => true
                    ],
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
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 1,
            "coins" => 0,
            "shards" => 0
        ];
    }
}