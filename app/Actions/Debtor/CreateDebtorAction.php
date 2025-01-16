<?php

declare(strict_types=1);

namespace App\Actions\Debtor;

use App\Actions\Action;
use App\Models\Debtor;
use Illuminate\Support\Facades\Log;

class CreateDebtorAction extends Action
{
    public function execute(mixed ...$attributes): Debtor
    {
        $debtor = Debtor::query()->firstOrCreate(
            values: $attributes
        );

        Log::info('Retrieved or created a debtor: {debtor}', ['debtor' => $debtor]);

        return $debtor;
    }
}
