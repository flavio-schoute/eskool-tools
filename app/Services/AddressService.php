<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class AddressService
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository
    ) {
        
    }

    public function createAddress(array $data): Address
    {
        return $this->addressRepository->create($data);
    }
}