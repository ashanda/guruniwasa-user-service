<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    
    use ResponseTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return $this->responseError([], 'Unauthorized', 401);
        }
        
    }

    
    protected function unauthenticated($request, array $guards)
    {
        
       
        throw new HttpResponseException(
            $this->responseError([], 'Unauthorized', 405)
        );
    }
}
