<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function all();

    public function create(array $data): Customer;
}
