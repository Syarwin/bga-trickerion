<?php

namespace Bga\Games\trickerionlegendsofillusion\Framework\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\Globals;

class Config extends Globals
{
    protected static array $data = [];
    protected static bool $initialized = false;
    protected static array $variables = [
        'engine' => 'obj',
        'lastEngine' => 'obj',
        'turnOrders' => 'obj',
        'endEngineCallback' => 'obj',

        'engineChoices' => 'int',
        'anytimeRecursion' => 'int',
    ];
}
