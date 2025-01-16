<?php

declare(strict_types=1);

namespace App\Actions;

abstract class Action
{
    public function __invoke(array | mixed ...$attributes): mixed
    {
        return $this->execute($attributes);
    }

    abstract public function execute(array | mixed ...$attributes): mixed;
}
