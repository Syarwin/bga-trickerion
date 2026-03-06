<?php

declare(strict_types=1);

namespace Bga\Games\trickerionlegendsofillusion\Framework\Engine;

use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\VisibleSystemException;
use Bga\Games\trickerionlegendsofillusion\Game;

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

        if ($this->getNode() !== null && $this->getNode()->getType() === Engine::NODE_LEAF) {
            $args['optionalAction'] = $this->getNode()->isOptional();
        }

        if ($this->getCustomStateDescription() !== null) {
            $args['customStateDescription'] = $this->getCustomStateDescription();
        }

        return $args;
    }

    protected function getActionArgs(int $activePlayerId): array
    {
        return [];
    }

    public function getNode() {
        return Engine::getNextUnresolved();
    }

    public function getNodeArgs($field = null, $default = null) {
        $args = [];

        $node = $this->getNode();
        if ($node !== null) {
            $args = $node->getArgs();
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
        $args = $this->getNodeArgs();
        return $args["optional"] ?? false;
    }

    public function isIrreversible() {
        $args = $this->getNodeArgs();
        return $args["irreversible"] ?? false;
    }

    public function isDoable() {
        return true;
    }

    public function isIndependent() {
        $args = $this->getNodeArgs();
        return $args["independent"] ?? false;
    }

    public function getDescription() {
        return null;
    }

    protected function resolve($args = null) {
        if ($this->isAutomatic()) {
            return Engine::autoResolveAction($args);
        } else {
            return Engine::resolveAction($args);
        }
    }

    protected function resolveIrreversible($args = null) {
        if ($this->isAutomatic()) {
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
}