<?php

namespace Bga\Games\trickerionlegendsofillusion\Models;

use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Assignments;
use Bga\Games\trickerionlegendsofillusion\Managers\Characters;
use Bga\Games\trickerionlegendsofillusion\Managers\Components;
use Bga\Games\trickerionlegendsofillusion\Managers\Magicians;
use Bga\Games\trickerionlegendsofillusion\Managers\Tricks;

/**
 * Class representing a Player
 *
 */
class Player extends \Bga\Games\trickerionlegendsofillusion\Framework\Models\Player
{
    protected $table = 'player';
    protected $primary = 'player_id';
    protected $customAttributes = [
        "shards" => ["player_shards", "int"],
        "coins" => ["player_coins", "int"],
        "initiative" => ["player_initiative", "int"],
        "colorName" => ["player_color_name", "str"],
    ];

    protected $staticAttributes = [];

    /*
    ██╗  ██╗███████╗██╗     ██████╗ ███████╗██████╗ ███████╗
    ██║  ██║██╔════╝██║     ██╔══██╗██╔════╝██╔══██╗██╔════╝
    ███████║█████╗  ██║     ██████╔╝█████╗  ██████╔╝███████╗
    ██╔══██║██╔══╝  ██║     ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║
    ██║  ██║███████╗███████╗██║     ███████╗██║  ██║███████║
    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝

    */

    public function incComponent(string $component, int $count, string $defaultLocation)
    {
        $component = Components::getFiltered($this->id, null, $component)
            ->first();

        if ($component->getCount() === 0) {
            $component->setLocation($defaultLocation);
        }

        $component->incCount($count);

        Game::get()->bga->notify->all("componentChanged", clienttranslate('${player_name} gets ${count} ${component}'), [
            "player_id" => $this->id,
            "count" => $count,
            "component" => $component,
        ]);
    }

    public function hasEnoughComponents(string $componentType, int $count): bool
    {
        $component = Components::getFiltered($this->id, null, $componentType)
            ->first();

        return $component->getEffectiveCount() >= $count;
    }

    public function getMagician()
    {
        return Magicians::getFiltered($this->getId(), Magicians::LOCATION_PLAYER)->first();
    }

    public function addCoins(int $count)
    {
        $this->incCoins($count);

        Game::get()->bga->notify->all("coinsChanged", clienttranslate('${player_name} gets ${coins} coins'), [
            "player_id" => $this->id,
            "coins" => $count,
            "newValue" => $this->getCoins(),
        ]);
    }

    public function payCoins(int $count)
    {
        $this->incCoins(-$count);

        Game::get()->bga->notify->all("coinsChanged", clienttranslate('${player_name} pays ${coins} coins'), [
            "player_id" => $this->id,
            "coins" => $count,
            "newValue" => $this->getCoins(),
        ]);
    }

    public function addShards(int $count)
    {
        $this->incShards($count);

        Game::get()->bga->notify->all("shardsChanged", clienttranslate('${player_name} gets ${shards} shards'), [
            "player_id" => $this->id,
            "shards" => $count,
            "newValue" => $this->getShards(),
        ]);
    }

    public function payShards(int $count) {
        $this->incShards(-$count);

        Game::get()->bga->notify->all("shardsChanged", clienttranslate('${player_name} pays ${shards} shards'), [
            "player_id" => $this->id,
            "shards" => $count,
            "newValue" => $this->getShards(),
        ]);
    }

    public function addFame(int $count)
    {
        $this->incScore($count);

        Game::get()->bga->notify->all("fameChanged", clienttranslate('${player_name} gets ${fame} fame'), [
            "player_id" => $this->id,
            "fame" => $count,
            "newValue" => $this->getScore(),
        ]);
    }

    public function addYields(array $yields) {
        $this->addFame($yields["fame"] ?? 0);
        $this->addCoins($yields["coins"] ?? 0);
        $this->addShards($yields["shards"] ?? 0);
    }

    public function hasSpecialist(string $characterType): bool
    {
        return Characters::getFiltered($this->id, null, $characterType)
            ->if("specialist")
            ->whereNot('location', [Characters::LOCATION_SUPPLY, Characters::LOCATION_INCOMING])
            ->count() > 0;
    }

    /*
    ███████╗ ██████╗ ██████╗ ██████╗ ██╗███╗   ██╗ ██████╗
    ██╔════╝██╔════╝██╔═══██╗██╔══██╗██║████╗  ██║██╔════╝
    ███████╗██║     ██║   ██║██████╔╝██║██╔██╗ ██║██║  ███╗
    ╚════██║██║     ██║   ██║██╔══██╗██║██║╚██╗██║██║   ██║
    ███████║╚██████╗╚██████╔╝██║  ██║██║██║ ╚████║╚██████╔╝
    ╚══════╝ ╚═════╝ ╚═════╝ ╚═╝  ╚═╝╚═╝╚═╝  ╚═══╝ ╚═════╝

    */

    public function scoreShards() {
        return $this->getShards();
    }

    public function scoreCoins() {
        return floor($this->getCoins() / 3);
    }

    public function scoreApprentices() {
        return Characters::getFiltered($this->id, null, Character::TYPE_APPRENTICE)
            ->whereNot("location", Characters::LOCATION_SUPPLY)
            ->count() * 2;
    }

    public function scoreSpecialists() {
        return Characters::getFiltered($this->id)
            ->if("specialist")
            ->whereNot("location", Characters::LOCATION_SUPPLY)
            ->count() * 3;
    }

    public function scoreSpecialAssignments() {
        return Assignments::getFiltered($this->id, Assignments::LOCATION_HAND)
            ->where("category", Assignment::CATEGORY_SPECIAL)
            ->count() * 2;
    }

    public function scoreTricks() {
        return Tricks::getFiltered($this->id, Tricks::LOCATION_PLAYER_ALL)
            ->reduce(function($total, $trick) {
                return $total + $trick->score();
            }, 0);
    }
}
