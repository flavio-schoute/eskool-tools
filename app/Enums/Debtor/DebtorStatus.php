<?php

namespace App\Enums\Debtor;

enum DebtorStatus: string
{
    case FIRST_REMINDER = 'First reminder';
    case SECOND_REMINDER = 'Second reminder';
    case COLLECTION_AGENCY = 'Collection agency';
    case FINISHED = 'Finished';
}
