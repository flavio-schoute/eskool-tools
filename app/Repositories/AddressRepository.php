<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Customer;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

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