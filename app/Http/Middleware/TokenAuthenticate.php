<?php
namespace App\Http\Middleware;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token  = $request->cookie('token');
        $result = JWTToken::ReadToken($token);
        if ($result === "unauthorized") {
            return ResponseHelper::error('Unauthorized', 401);
        } else {
            $request->headers->set('id', $result->id);
            $request->headers->set('email', $result->email);
            return $next($request);
        }

    }
}
