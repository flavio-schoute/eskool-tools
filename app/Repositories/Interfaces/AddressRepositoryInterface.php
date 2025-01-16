<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Address;

interface AddressRepositoryInterface
{
    public function all();

    public function create(array $data): Address;
}
