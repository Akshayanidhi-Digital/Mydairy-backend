<?php

namespace App\Services;

use Laravel\Passport\PersonalAccessTokenFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\Tokens;

class TokenService
{
    protected $tokenFactory;

    public function __construct(PersonalAccessTokenFactory $tokenFactory)
    {
        $this->tokenFactory = $tokenFactory;
    }

    public function createToken($name, $scopes = [])
    {
        $user = Auth::user();
        $userType = $user instanceof \App\Models\User ? 'user' : 'transporter';
        

    }
}
