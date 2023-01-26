<?php

namespace App\Http\Controllers;

use App\Entities\LoggedUser;
use App\Contracts\ILoggedUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\Http\UnauthorizedException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function getUser(): ILoggedUser
    {
        $guard = app()->make(Guard::class);

        $user = $guard->user();
        if (!$user) {
            throw new UnauthorizedException();
        }

        return new LoggedUser($user->id);
    }
}
