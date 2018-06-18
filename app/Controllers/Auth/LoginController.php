<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;
use Respect\Validation\Validator as V;

class LoginController extends Controller {
	public function login($request, $response) {

		$validation = $this->ValidateLoginRequest($request);

    if ($validation->failed()) {
        return $response->withJson(['errors' => $validation->getErrors()], 422);
    }

		$auth = $this->auth->attempt(
			$request->getParam('username'),
			$request->getParam('password')
		);

		if (!$auth) {
			return $response->withJson(['status' => false, 'message' => 'Invalid username or password'])->withStatus(422);
		}

		$auth->{'token'} = $this->auth->generateToken($auth);
		$transform = new Item($auth, new UserTransformer());
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson(['user' => $data])->withStatus(200);
	}

	private function ValidateLoginRequest($request)
	{
		return $this->validator->validate($request,
      [
        'username' => v::noWhitespace()->notEmpty(),
        'password' => v::noWhitespace()->notEmpty(),
      ]);
	}
}