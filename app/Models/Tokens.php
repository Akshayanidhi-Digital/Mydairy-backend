<?php

namespace App\Models;

use Laravel\Passport\Token as PassportToken;


class Tokens extends PassportToken
{
    protected $fillable = ['user_id','user_type','client_id', 'name', 'scopes', 'revoked', 'expires_at'];
}
