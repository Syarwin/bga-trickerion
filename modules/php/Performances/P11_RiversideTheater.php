<?php

namespace Bga\Games\trickerionlegendsofillusion\Performances;

use Bga\Games\trickerionlegendsofillusion\Models\Performance;

class P11_RiversideTheater extends Performance
{
    public function __construct($row)
    {
        parent::__construct($row);
        $this->type = 'P11_RiversideTheater';
        $this->theater = Performance::THEATER_RIVERSIDE;
        $this->name = clienttranslate('Riverside Theater');
        $this->slots = [
            "1.0" => [
                "x" => 1,
                "y" => 0,
                "links" => [
                    [
                        "direction" => self::LINK_DIRECTION_LEFT,
                        "shard" => true
                    ]
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
                ]
            ],
        ];
        $this->bonus = [
            "fame" => 0,
            "coins" => 2,
            "shards" => 0
        ];
    }
}