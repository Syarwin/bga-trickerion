<?php

namespace Bga\Games\trickerionlegendsofillusion\Managers;

use Bga\GameFramework\NotificationMessage;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\CachedPieces;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Collection;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Models\Assignment;

class Assignments extends CachedPieces
{
    protected static $datas = null;
    protected static $table = 'assignment';
    protected static $prefix = 'assignment_';
    protected static $customFields = ["assignment_type", "player_id"];
    protected static $autoIncrement = true;
    protected static $autoremovePrefix = false;
    protected static $autoreshuffle = false;
    protected static $autoreshuffleCustom = [];
    
    public static function autoreshuffleListener($location) {}

    protected static function cast($raw)
    {
        return self::getAssignmentInstance($raw["assignment_type"], $raw);
    }

    public static function getAssignmentInstance($type, $data = null)
    {
        $className = "Bga\Games\\trickerionlegendsofillusion\Assignments\\$type";
        return new $className($data);
    }

    public static function getUiData($playerId = null)
    {
        return [
            "hand" => self::getFiltered($playerId, Assignments::LOCATION_HAND)->toArray(),
            "deckRemaining" => [
                self::LOCATION_DOWNTOWN_DECK => self::countInLocation(self::LOCATION_DOWNTOWN_DECK),
                self::LOCATION_WORKSHOP_DECK => self::countInLocation(self::LOCATION_WORKSHOP_DECK),
                self::LOCATION_MARKET_ROW_DECK => self::countInLocation(self::LOCATION_MARKET_ROW_DECK),
                self::LOCATION_THEATER_DECK => self::countInLocation(self::LOCATION_THEATER_DECK),
            ],
            "discards" => [
                self::LOCATION_DOWNTOWN_DISCARD => self::getInLocationOrdered(self::LOCATION_DOWNTOWN_DISCARD)->toArray(),
                self::LOCATION_WORKSHOP_DISCARD => self::getInLocationOrdered(self::LOCATION_WORKSHOP_DISCARD)->toArray(),
                self::LOCATION_MARKET_ROW_DISCARD => self::getInLocationOrdered(self::LOCATION_MARKET_ROW_DISCARD)->toArray(),
                self::LOCATION_THEATER_DISCARD => self::getInLocationOrdered(self::LOCATION_THEATER_DISCARD)->toArray(),
            ],
            "assigned" => [
                "my" => self::getFiltered($playerId, self::LOCATION_ASSIGNED_ANY)->toArray(),
                "other" => Players::getOpponents($playerId)->map(function($opponent) { 
                    return [
                        "revealed" => self::getFiltered($opponent->id, self::LOCATION_ASSIGNED_FACEUP)->toArray(),
                        "hidden" => self::getFiltered($opponent->id, self::LOCATION_ASSIGNED_FACEDOWN)->map(function($assignment) {
                            return [
                                "location" => $assignment->getLocation(),
                                "state" => $assignment->getState(),
                            ];
                        })->toArray(),
                    ];
                })
            ]
        ];
    }

    /*
  ███████╗███████╗████████╗██╗   ██╗██████╗
  ██╔════╝██╔════╝╚══██╔══╝██║   ██║██╔══██╗
  ███████╗█████╗     ██║   ██║   ██║██████╔╝
  ╚════██║██╔══╝     ██║   ██║   ██║██╔═══╝
  ███████║███████╗   ██║   ╚██████╔╝██║
  ╚══════╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝
  */

    /* Creation of the cards */
    public static function setupNewGame()
    {
        // Load list of cards
        include dirname(__FILE__) . '/../Assignments/list.php';

        // Create cards
        $assignments = [];
        foreach ($assignmentTypes as $type) {
            $assignment = self::getAssignmentInstance($type);

            $initialData = self::getInitialData($assignment);

            if (!Globals::isDarkAlley() && $assignment->getBoardLocation() === Assignment::BOARD_LOCATION_DARK_ALLEY) {
                continue;
            }

            $data = [
                'assignment_type' => $type,
                'location' => $initialData['location'],
                'nbr' => $initialData['nbr'],
            ];

            if ($assignment->getCategory() === Assignment::CATEGORY_SPECIAL) {
                $assignments[] = $data;
            } else {
                foreach (Players::getAll() as $playerId => $_) {
                    $data['player_id'] = $playerId;
                    $assignments[] = $data;
                }
            }
        }

        // Create the assignments
        self::create($assignments, null);

        self::shuffle(self::LOCATION_THEATER_DECK);
        self::shuffle(self::LOCATION_DOWNTOWN_DECK);
        self::shuffle(self::LOCATION_WORKSHOP_DECK);
        self::shuffle(self::LOCATION_MARKET_ROW_DECK);
    }

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public static function resetAssignments(int $playerId) {
        $assignments = self::getFiltered($playerId, self::LOCATION_ASSIGNED_ANY)
            ->ForEach(function(Assignment $assignment) {
                $assignment->setLocation(self::LOCATION_HAND);
            });

        Game::get()->bga->notify->all('assignmentsReset', clienttranslate('${player_name} decided to reassign the characters'), [
            'player_id' => $playerId,
             '_private' => [
                $playerId => new NotificationMessage(clienttranslate('You decided to reassign the characters'), [
                    "assignments" => $assignments->toArray()
                ]),
             ],
        ]);
    }

    private static function getInitialData($assignment) {
        if ($assignment->getCategory() === Assignment::CATEGORY_SPECIAL) {
            return [
                "location" => self::getSpecialAssignmentInitialLocation($assignment->getBoardLocation()),
                "nbr" => 1
            ];
        } else {
            return [
                'location' => self::LOCATION_HAND,
                'nbr' => self::getPermanentAssignmentInitialCopies($assignment->getBoardLocation()),
            ];
        }
    }

    public static function getSpecialAssignmentInitialLocation($boardLocation) {
        return [
            Assignment::BOARD_LOCATION_THEATER => self::LOCATION_THEATER_DECK,
            Assignment::BOARD_LOCATION_DOWNTOWN => self::LOCATION_DOWNTOWN_DECK,
            Assignment::BOARD_LOCATION_WORKSHOP => self::LOCATION_WORKSHOP_DECK,
            Assignment::BOARD_LOCATION_MARKET_ROW => self::LOCATION_MARKET_ROW_DECK,
        ][$boardLocation];
    }
    
    public static function getSpecialAssignmentDiscardLocation($boardLocation) {
        return [
            Assignment::BOARD_LOCATION_THEATER => self::LOCATION_THEATER_DISCARD,
            Assignment::BOARD_LOCATION_DOWNTOWN => self::LOCATION_DOWNTOWN_DISCARD,
            Assignment::BOARD_LOCATION_WORKSHOP => self::LOCATION_WORKSHOP_DISCARD,
            Assignment::BOARD_LOCATION_MARKET_ROW => self::LOCATION_MARKET_ROW_DISCARD,
        ][$boardLocation];
    }

    private static function getPermanentAssignmentInitialCopies($boardLocation) {
        if (!Globals::isDarkAlley()) {
            return [
                Assignment::BOARD_LOCATION_THEATER => 3,
                Assignment::BOARD_LOCATION_DOWNTOWN => 2,
                Assignment::BOARD_LOCATION_WORKSHOP => 2,
                Assignment::BOARD_LOCATION_MARKET_ROW => 2,
                Assignment::BOARD_LOCATION_DARK_ALLEY => 0, 
            ][$boardLocation];
        }

        return [
            Assignment::BOARD_LOCATION_THEATER => 2,
            Assignment::BOARD_LOCATION_DOWNTOWN => 1,
            Assignment::BOARD_LOCATION_WORKSHOP => 1,
            Assignment::BOARD_LOCATION_MARKET_ROW => 1,
            Assignment::BOARD_LOCATION_DARK_ALLEY => 1,
        ][$boardLocation];
    }

    public static function getAvailableAssignments($playerId = null) {
        $facupAssignments = self::getInLocation(self::LOCATION_ASSIGNED_FACEUP);
        if ($playerId !== null) {
            $facupAssignments = $facupAssignments->where("playerId", $playerId);
        }

        return $facupAssignments
            ->filter(function($assignment) {
                return Characters::getFiltered($assignment->getPlayerId(), Characters::LOCATION_IDLE_ANY)
                    ->where("id", $assignment->getState())
                    ->count() > 0;
            })
            ->map(function($assignment) {
                $character = Characters::get($assignment->getState());
                $possibleLocations = $character->getPossibleLocations($assignment->getBoardLocation());

                return [
                    "assignment" => $assignment,
                    "character" => $character,
                    "possibleLocations" => $possibleLocations
                ];
            })->toArray();
    }

    public static function roundMaintenance() {
        $playedAssignments = self::getInLocation(self::LOCATION_ASSIGNED_ANY);

        $permanentAssignments = $playedAssignments->where("category", Assignment::CATEGORY_PERMANENT);
        $specialAssignments = $playedAssignments->where("category", Assignment::CATEGORY_SPECIAL);
        $facedownSpecialAssignments = $specialAssignments->where("location", self::LOCATION_ASSIGNED_FACEDOWN);

        $permanentAssignments
            ->merge($facedownSpecialAssignments)
            ->update("location", self::LOCATION_HAND)
            ->update("state", 0);

        Game::get()->bga->notify->all('assignmentsReturned', clienttranslate('All permanent and face-down assignment cards are returned to player hands'), []);

        $faceupSpecialAssignments = $specialAssignments->where("location", self::LOCATION_ASSIGNED_FACEUP);
        $faceupSpecialAssignments
            ->update("location", function($assignment) {
                return self::getSpecialAssignmentDiscardLocation($assignment->getBoardLocation());
            })
            ->update("state", 0)
            ->update("playerId", null);

        Game::get()->bga->notify->all('assignmentsDiscarded', clienttranslate('All faceup special assignment cards are discarded'), []);
    }

    /*
   ██████╗ ██████╗ ███╗   ██╗███████╗████████╗ █████╗ ███╗   ██╗████████╗███████╗
  ██╔════╝██╔═══██╗████╗  ██║██╔════╝╚══██╔══╝██╔══██╗████╗  ██║╚══██╔══╝██╔════╝
  ██║     ██║   ██║██╔██╗ ██║███████╗   ██║   ███████║██╔██╗ ██║   ██║   ███████╗
  ██║     ██║   ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║╚██╗██║   ██║   ╚════██║
  ╚██████╗╚██████╔╝██║ ╚████║███████║   ██║   ██║  ██║██║ ╚████║   ██║   ███████║
   ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚══════╝

  */

    const LOCATION_HAND = 'hand';
    const LOCATION_THEATER_DECK = 'theater_deck';
    const LOCATION_DOWNTOWN_DECK = 'downtown_deck';
    const LOCATION_WORKSHOP_DECK = 'workshop_deck';
    const LOCATION_MARKET_ROW_DECK = 'market_row_deck';
    const LOCATION_THEATER_DISCARD = 'theater_discard';
    const LOCATION_DOWNTOWN_DISCARD = 'downtown_discard';
    const LOCATION_WORKSHOP_DISCARD = 'workshop_discard';
    const LOCATION_MARKET_ROW_DISCARD = 'market_row_discard';
    const LOCATION_ASSIGNED_FACEUP = 'assigned-faceup';
    const LOCATION_ASSIGNED_FACEDOWN = 'assigned-facedown';
    const LOCATION_ASSIGNED_ANY = "assigned-%";
    const LOCATION_DRAWN = 'drawn';
}
