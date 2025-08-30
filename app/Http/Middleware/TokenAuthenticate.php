<?php
namespace App\Http\Middleware;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticate
{
    // Stateless auth: read JWT from cookie 'token'; inject user id/email headers or reject.
    public function handle(Request $request, Closure $next): Response
    {
        $result = JWTToken::ReadToken($request->cookie('token'));
        if ($result === 'unauthorized') {
            return ResponseHelper::unauthorized();
        }
        $request->headers->set('id', $result->id);
        $request->headers->set('email', $result->email);
        return $next($request);
    }
}
