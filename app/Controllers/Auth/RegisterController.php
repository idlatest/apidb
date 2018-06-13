<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;
use Respect\Validation\Validator as V;

class RegisterController extends Controller {
	public function register($request, $response) {

		$validation = $this->validateRegisterRequest($request);

    if ($validation->failed()) {
        return $response->withJson(['errors' => $validation->getErrors()], 422);
    }

		$user = User::create([
			'first_name' => $request->getParam('first_name'),
			'last_name' => $request->getParam('last_name'),
			'username' => $request->getParam('username'),
			'email' => $request->getParam('email'),
      'account_type' => $request->getParam('account_type'),
			'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
		]);

    $transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson(['user' => $data])->withStatus(200);
	}

	protected function validateRegisterRequest($request)
  {
    return $this->validator->validate($request,
      [
      	'first_name' => V::noWhitespace()->notEmpty()->alpha(),
      	'last_name' => V::noWhitespace()->notEmpty()->alpha(),
        'email'    => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
        'username' => v::noWhitespace()->notEmpty()->usernameAvailable(),
        'account_type' => v::noWhitespace()->notEmpty(),
        'password' => v::noWhitespace()->notEmpty(),
      ]);
  }
}