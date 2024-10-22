<?php

namespace App\Actions;

use Nette\NotImplementedException;

abstract class AbstractAction
{
    public function __invoke()
    {
        if (!method_exists($this, 'execute')) {
            throw new NotImplementedException('Method execute not implemented');
        }
    }
}
