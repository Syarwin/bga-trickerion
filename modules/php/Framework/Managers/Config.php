<?php

namespace Bga\Games\trickerionlegendsofillusion\Framework\Managers;

use Bga\Games\trickerionlegendsofillusion\Framework\Db\Globals;

class Config extends Globals
{
    protected static $data = [];
    protected static $initialized = false;
    protected static $variables = [
        'engine' => 'obj', 
        'lastEngine' => 'obj', 
        'turnOrders' => 'obj',
        'endEngineCallback' => 'obj', 
        
        'engineChoices' => 'int', 
        'anytimeRecursion' => 'int', 
    ];
}