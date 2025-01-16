<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all()
    {
        // Maybe make this select or getAll
        return Customer::query()->get();
    }

    public function create(array $data): Customer
    {
        return Customer::query()->create($data);
    }
}
