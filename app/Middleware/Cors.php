<?php

namespace App\Middleware;

class Cors
{
  public function __invoke($request, $response, callable $next)
  {
    $response = $next($request, $response);

    return $response
    	->withHeader('Access-Control-Allow-Origin', getenv('CORS_ALLOWED_ORIGINS'))
      ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
  }
}
