<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Framework\Engine\Engine;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;
use Bga\Games\trickerionlegendsofillusion\Managers\TrickMarkers;
use Bga\Games\trickerionlegendsofillusion\States\Actions\GetShards;

/**
 * Performance: model utilities and metadata for a performance.
 *
 * @property int $id The ID of the performance
 * @property string $type The type of the performance
 * @property int $location The location of the performance
 * @property string $name The name of the performance
 * @property string $theater The theater of the performance
 * @property object $slots The slots payload of the performance
 * @property object $bonus The bonus payload of the performance
 */
class Performance extends  \Bga\Games\trickerionlegendsofillusion\Framework\Db\DB_Model
{
    protected $table = 'performance';
    protected $primary = 'performance_id';
    protected $attributes = [
        'id' => ['performance_id', 'int'],
        'type' => ['performance_type', "string"],
        'location' => 'performance_location',
        'state' => ['performance_state', 'int'],
    ];

    protected $staticAttributes = [
        ['name', 'str'],
        ['theater', 'str'],
        ['slots', 'object'],
        ['bonus', 'object'],
    ];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function getSlotDirections($slotId) {
        return array_map(function($link) {
            return $link["direction"];
        }, $this->getSlots()[$slotId]["links"]);
    }

    public function getSlotLink($slotId, $direction) {
        foreach ($this->getSlots()[$slotId]["links"] as $link) {
            if ($link["direction"] === $direction) {
                return $link;
            }
        }
        throw new \Exception("Link not found for slot $slotId and direction $direction");
    }

    public function canAddTrick(Trick $trick) : bool {
        $performanceMarkers = TrickMarkers::getOnPerformance($this->getId());
        $playerMarkers = $performanceMarkers->where("playerId", $trick->getPlayerId());
        $playerSuits = $playerMarkers->pluck("suit")->toArray();
        
        return !in_array($trick->getSuit(), $playerSuits);
    }

    public function getAvailableSlots() {
        $performanceMarkers = TrickMarkers::getOnPerformance($this->getId());
        
        $markerSlots = $performanceMarkers->pluck("slotId")->toArray();
        $allSlots = $this->getSlots();

        return array_filter($allSlots, function($key) use ($markerSlots) {
            return !in_array($key, $markerSlots);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function addLinkRewards($slotId) {
        $directions = $this->getSlotDirections($slotId);
        $rewardActions = [];
        $playerId = $this->getTrickMarkerInSlot($slotId)->getPlayerId();

        foreach ($directions as $direction) {
            $link = $this->getSlotLink($slotId, $direction);
            
            if ($this->checkMatch($slotId, $direction)) {
                Game::get()->bga->notify->all("linkMatched", clienttranslate('${player_name} creates a link and will get rewards'), [
                    "player_id" => $playerId,
                    "performanceId" => $this->getId(),
                    "slotId" => $slotId,
                    "direction" => $direction
                ]);
                $rewardActions[] = $this->getTrickInSlot($slotId)->getLinkRewardActions();
                
                if ($link["shard"]) {
                    $otherPlayerId = $this->getTrickMarkerInSlot($this->getLinkedSlotId($slotId, $direction))->getPlayerId();
                    Game::get()->bga->notify->all("linkWithShardMatched", clienttranslate('There is a shard in created link so both ${player_name} and ${player_name2} will get rewards'), [
                        "player_id" => $playerId,
                        "player_id2" => $otherPlayerId,
                        "performanceId" => $this->getId(),
                        "slotId" => $slotId,
                        "direction" => $direction
                    ]);

                    $rewardActions[] = [
                        "type" => Engine::NODE_PARALLEL,
                        "customDescription" => [
                            "log" => clienttranslate('${player_name} and ${player_name2} get a shard'),
                            "args" => [
                                "player_name" => Players::get($playerId)->getName(),
                                "player_name2" => Players::get($otherPlayerId)->getName(),
                            ]
                        ],
                        "children" => [
                            [
                                "state" => GetShards::class,
                                "independent" => true,
                                "args" => [
                                    "amount" => 1
                                ]
                            ],
                            [
                                "state" => GetShards::class,
                                "customDescription" => [
                                    "log" => clienttranslate('${player_name} gets a shard'),
                                    "args" => [
                                        "player_name" => Players::get($otherPlayerId)->getName()
                                    ]
                                ],
                                "independent" => true,
                                "args" => [
                                    "amount" => 1,
                                    "playerId" => $otherPlayerId,
                                ]
                            ],
                        ]
                    ];
                }
            }
        }

        if (count($rewardActions) > 0) {
            Engine::insertAsChild([
                "type" => Engine::NODE_PARALLEL,
                "children" => $rewardActions
            ]);
        }
    }

    private function getTrickMarkerInSlot($slotId) {
        return TrickMarkers::getOnPerformance($this->getId())
            ->where("slotId", $slotId)
            ->first();
    }

    private function getTrickInSlot($slotId) {
        $trickMarker = $this->getTrickMarkerInSlot($slotId);
        if (!$trickMarker) {
            return null;
        }
        return $trickMarker->getTrick();
    }   

    private function getLinkedSlotId($slotId, $direction) {
        $x = $this->getSlots()[$slotId]["x"];
        $y = $this->getSlots()[$slotId]["y"];

        return match ($direction) {
            self::LINK_DIRECTION_DOWN => "$x." . ($y + 1),
            self::LINK_DIRECTION_UP => "$x." . ($y - 1),
            self::LINK_DIRECTION_RIGHT => ($x + 1) . ".$y",
            self::LINK_DIRECTION_LEFT => ($x - 1) . ".$y",
        };
    }

    private function checkMatch($slotId, $direction) {
        $linkedSlotId = $this->getLinkedSlotId($slotId, $direction);
        $originMarker = $this->getTrickMarkerInSlot($slotId);
        $linkedMarker = $this->getTrickMarkerInSlot($linkedSlotId);

        if (!$originMarker || !$linkedMarker) {
            return false;
        }

        $originMarkerLinkCategory = $originMarker->getCategoryForDirection($direction);
        $linkedMarkerLinkCategory = $linkedMarker->getCategoryForDirection($this->getOppositeDirection($direction));

        return $originMarkerLinkCategory === $linkedMarkerLinkCategory;
    }

    private function getOppositeDirection($direction) {
        return match ($direction) {
            self::LINK_DIRECTION_DOWN => self::LINK_DIRECTION_UP,
            self::LINK_DIRECTION_UP => self::LINK_DIRECTION_DOWN,
            self::LINK_DIRECTION_RIGHT => self::LINK_DIRECTION_LEFT,
            self::LINK_DIRECTION_LEFT => self::LINK_DIRECTION_RIGHT,
        };
    }

    /*
    ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
    ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
    ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
    ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
    ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
    ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

    */    

    const THEATER_RIVERSIDE = 'riverside_theater';
    const THEATER_GRAND_MAGORIAN = 'grand_magorian';
    const THEATER_MAGNUS_PANTHEON = 'magnus_pantheon';

    const LINK_DIRECTION_DOWN = 'down';
    const LINK_DIRECTION_RIGHT = 'right';
    const LINK_DIRECTION_UP = 'up';
    const LINK_DIRECTION_LEFT = 'left';
}
