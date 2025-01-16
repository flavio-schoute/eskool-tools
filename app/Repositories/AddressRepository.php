<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{
    public function all()
    {
        // Maybe make this select or getAll
        return Address::query()->get();
    }

    public function create(array $data): Address
    {
        return Address::query()->create($data);
    }
}
