<?php

namespace App\Enums;

enum Permission: string
{
    case CHANGE_COMMISSION_PERCENTAGE = 'change-commission-percentage';
    case MODIFY_COMMISSION_SHEETS = 'modify-commission-sheets';

    CASE DISABLE_TEAM_MEMBER_ACCOUNT = 'disable-team-member-account';

    CASE CLAIM_ORDER = 'claim-order';
    CASE VIEW_ORDER = 'view-order';

    CASE VIEW_COMMISSION_OVERVIEW = 'view-commission-overview';
    CASE GENERATE_EXPORT_COMMISSION_OVERVIEW = 'generate-export-commission-overview';
}
