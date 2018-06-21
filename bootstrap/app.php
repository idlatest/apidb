<?php
use Respect\Validation\Validator as V;
session_start();

require __DIR__ . '/../vendor/autoload.php';

// Define root path
defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') ?: define('ROOT', dirname(__DIR__) . DS);

// Load .env file
if (file_exists(ROOT . '.env')) {
	$dotenv = new Dotenv\Dotenv(ROOT);
	$dotenv->load();
}

// tijani.nwadei@gmail.com sendng of cv

require __DIR__ . '/pagination.php';

$app = new \Slim\App([
	"settings" => [
		"displayErrorDetails" => getenv('APP_DEBUG'),
		'app' => [
			'name' => getenv('APP_NAME'),
			'url' => getenv('APP_URL'),
			'env' => getenv('APP_ENV'),
		],
		'db' => [
			'driver' => getenv('DB_DRIVER'),
			'host' => getenv('DB_HOST'),
			'database' => getenv('DB_DATABASE'),
			'username' => getenv('DB_USERNAME'),
			'password' => getenv('DB_PASSWORD'),
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prifix' => '',
		],
		'cors' => null !== getenv('CORS_ALLOWED_ORIGINS') ? getenv('CORS_ALLOWED_ORIGINS') : '*',
		'jwt' => [
			'secret' => getenv('JWT_SECRET'),
			'secure' => false,
			"header" => "Authorization",
			"regexp" => "/Token\s+(.*)$/i",
			'passthrough' => ['OPTIONS'],
		],
	],
]);

$app->add(new \App\Middleware\Cors());

$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['RegisterController'] = function ($container) 
{
	return new \App\Controllers\Auth\RegisterController($container);
};

$container['LoginController'] = function ($container) 
{
	return new \App\Controllers\Auth\LoginController($container);
};

$container['UserController'] = function ($container)
{
	return new \App\Controllers\User\UserController($container);
};

$container['CategoryController'] = function ($container)
{
	return new \App\Controllers\CategoryController($container);
};

$container['ClientController'] = function ($container) {
	return new \App\Controllers\Client\ClientController($container);
};

$container['AirportController'] = function ($container)
{
	return new \App\Controllers\AirportController($container);
};

$container['auth'] = function ($container) 
{
	return new \App\Auth\User\Auth($container['settings']);
};

$container['clientAuth'] = function ($container) 
{
	return new \App\Auth\Client\Auth($container['settings']);
};

$container['ReviewController'] = function ($container)
{
	return new \App\Controllers\ReviewController($container);
};


$container['fractal'] = function ()
{
	$manager = new League\Fractal\Manager;
	$manager->setSerializer(new League\Fractal\Serializer\ArraySerializer());
	return $manager;
};

$container['jwt'] = function ($container)
{
	return new Tuupola\Middleware\JwtAuthentication($container['settings']['jwt']);
};

$container['validator'] = function ($container) {
	return new \App\Validation\Validator;
};

V::with('App\\Validation\\Rules\\');

// $container['optionalAuth'] = function ($container)
// {
// 	return new App\Middleware\OptionalAuth($container);
// };

require __DIR__ . '/../routes/api.php';