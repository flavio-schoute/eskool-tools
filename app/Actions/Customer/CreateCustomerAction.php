<?php

namespace App\Actions\Customer;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Notifications\Action;

class CreateCustomerAction extends Action
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository
    ) {     
    }

    public function execute(array $data)
    {   
        $this->customerRepository->create($data);
    }
}