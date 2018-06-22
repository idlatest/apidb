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

<<<<<<< HEAD
$app->get('/client', 'ClientController:index')->add($jwtAuth);
=======
//review routes
$app->get('/reviews', 'ReviewController:store');

>>>>>>> 730e38a24b55dac61284c811f90f185e045e147e
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

$app->get('/continents', function ($request, $response) use ($container) {
	$db = $container->db;

	$continents = $db->table('apidb_continents')->get();

	return $response->withJson([
		'status' => true,
		'continents' => [
			'data' => $continents
		],
	], 200);
})->add($jwtAuth);

$app->get('/countries', 'CountryController:index')->add($jwtAuth);

$app->get('/universities', 'UniversityController:index')->add($jwtAuth);
$app->get('/universities/{id}', 'UniversityController:show')->add($jwtAuth);