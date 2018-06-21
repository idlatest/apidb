<?php

use App\Models\Client;
use App\Auth\Client\Auth;
use Firebase\JWT\JWT;

$jwtAuth = $container->get('jwt');

$app->group('/auth', function () {
	$this->post('/register', 'RegisterController:register');
	$this->post('/login', 'LoginController:login');
});

$app->get('/user', 'UserController:show')->add($jwtAuth);
$app->get('/categories', 'CategoryController:index');

//review routes
$app->get('/reviews', 'ReviewController:store');

$app->post('/client', 'ClientController:store')->add($jwtAuth);

$app->post('/oauth/authorize', function ($request, $response) use ($container) {
	$client = Client::where('app_key', $request->getParam('key'))->first();

	if (! $client->count()) {
		return $response->withStatus(401);
	}

	$token = (new Auth($container->get('settings')))->generateToken($client);

	return $response->withJson(['token' => $token]);
});

$app->group('/airports', function () {
	$this->get('', 'AirportController:index');
	$this->get('/', 'AirportController:index');
	$this->get('/{code}', 'AirportController:show');
})->add($jwtAuth);