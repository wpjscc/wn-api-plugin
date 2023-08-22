<?php

namespace Wpjscc\Api\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    public function service()
    {
        return $this->hasOne(Service::class, 'token_id');
    }
}
