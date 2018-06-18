<?php

namespace App\Controllers\User;

use App\Auth\User\Auth;
use App\Controllers\Controller;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;

class UserController extends Controller
{
	public function show($request, $response)
	{
		$user = $this->auth->requestUser($request);
		
		if (!$user) {
			return $response->withStatus(404)->withJson(['user' => 'User not found']);
		}

		$transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson(['user' => $data]);
	}
}
