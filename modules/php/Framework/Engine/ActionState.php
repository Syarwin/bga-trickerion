<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\Framework\Engine;

use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\VisibleSystemException;
use Bga\Games\trickerionlegendsofillusion\Framework\Db\Log;
use Bga\Games\trickerionlegendsofillusion\Framework\Managers\Config;
use Bga\Games\trickerionlegendsofillusion\Game;
use Bga\Games\trickerionlegendsofillusion\Managers\Players;


class ActionState extends \Bga\GameFramework\States\GameState
{
    function __construct(
        protected Game $game,
        public int $id, 
        public \Bga\GameFramework\StateType $type,
        public ?string $name = null,
        public string $description = '',
        public string $descriptionMyTurn = '',
        public array $transitions = [],
        public bool $updateGameProgression = false,
        public string|int|null $initialPrivate = null,
        protected ?AbstractNode $node = null
    ) {
        parent::__construct($game,
            id: $id,
            type: $type,
            name: $name,
            description: $description,
            descriptionMyTurn: $descriptionMyTurn,
            transitions: $transitions,
            updateGameProgression: $updateGameProgression,
            initialPrivate: $initialPrivate,
        );
    }

    public function getCustomStateDescription() {
        return null;
    }

    final public function getArgs(int $activePlayerId): array
    {
        return array_merge(
            $this->getGeneralArgs($activePlayerId),
            $this->getActionArgs($activePlayerId)
        );
    }

    protected function getGeneralArgs(int $activePlayerId): array
    {
        $args = [];

        $isOptional = $this->isOptional();

        if (is_null($isOptional)){
            $isOptional = false;
            if ($this->getNode() !== null && $this->getNode()->getType() === Engine::NODE_LEAF) {
                $isOptional = $this->getNode()->isOptional();
            } 
        }
                
        $args['optionalAction'] = $isOptional;

        if ($this->getCustomStateDescription() !== null) {
            $args['customStateDescription'] = $this->getCustomStateDescription();
        }

        $args['anytimeActions'] = $this->getAnytimeActions();

        return $args;
    }

    protected function getActionArgs(int $activePlayerId): array
    {
        return [];
    }

    private function getAnytimeActions(): array
    {
        if (!$this->getNodeArgs("addAnytimeActions", true)) {
            return [];
        }

        $freeActionsDir = __DIR__ . '/../../States/Actions/Anytime/';
        $namespace = 'Bga\Games\trickerionlegendsofillusion\States\Actions\Anytime\\';

        $actions = [];
        foreach (glob($freeActionsDir . '*.php') as $file) {
            $actions[] = ['state' => $namespace . basename($file, '.php')];
        }

        $anytimeActions = [];
        foreach ($actions as $action) {
            $tree = Engine::buildTree($action);
            if ($tree->isDoable(Players::getActiveId())) {
                $anytimeActions[] = [
                    'description' => $tree->getDescription(true),
                    "id" => $action["state"]
                ];
            }
        }

        return $anytimeActions;
    }

    public function getNode() {
        if ($this->node !== null) {
            return $this->node;
        }

        return Engine::getNextUnresolved();
    }

    public function getNodeArgs($field = null, $default = null) {
        $args = [];

        $node = $this->getNode();
        if ($node !== null) {
            $args = $node->getArgs() ?? [];
        }

        if ($field !== null) {
            return $args[$field] ?? $default;
        }

        return $args;
    }

    public function isAutomatic() {
        return $this->type === StateType::GAME;
    }

    public function isOptional() {
        return null;
    }

    public function isIrreversible() {
        return $this->getNode()->getInfo()["irreversible"] ?? false;
    }

    public function isDoable($playerId) {
        return true;
    }

    public function isIndependent() {
        return $this->getNode()->getInfo()["independent"] ?? false;
    }

    public function getDescription() {
        return null;
    }

    protected function getPlayerId() {
        $argsPlayerId = $this->getNodeArgs("playerId");
        return $argsPlayerId ?? Players::getActiveId();
    }

    protected function getPlayer() {
        return Players::get($this->getPlayerId());
    }

    protected function resolve($args = []) {
        if ($this->isAutomatic() || ($args["automatic"] ?? false)) {
            return Engine::autoResolveAction($args);
        } else {
            return Engine::resolveAction($args);
        }
    }

    protected function resolveIrreversible($args = []) {
        if ($this->isAutomatic() || ($args["automatic"] ?? false)) {
            return Engine::autoResolveIrreversibleAction($args);
        } else {
            return Engine::resolveIrreversibleAction($args);
        }
    }

    #[PossibleAction]
    public function actPassOptionalAction()
    {
        if (!$this->getNode()->isOptional()) {
            throw new VisibleSystemException('This action is not optional');
        }

        return Engine::resolve(Engine::PASS);
    }

    #[PossibleAction]
    public function actAnytimeAction(string $actionId)
    {
        Log::step();

        Config::incEngineChoices();
        Engine::prependAtRoot([
            "state" => $actionId,
            "args" => [
                "addAnytimeActions" => false
            ]
        ]);

        return Engine::proceed();
    }

    function zombie(int $playerId) {
        Game::get()->bga->notify->all("message", clienttranslate('${player_name} is a zombie, skipping their turn'), [
            "player_id" => $playerId,
        ]);
        return $this->resolve([
            "automatic" => true
        ]);
    }
}