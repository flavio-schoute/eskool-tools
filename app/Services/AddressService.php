<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;

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
