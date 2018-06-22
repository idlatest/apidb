<?php

namespace App\Controllers\Client;

use App\Models\Client;
use App\Controllers\Controller;
use App\Transformers\ClientTransformer;
use League\Fractal\Resource\{
	Item,
	Collection
};
use App\Services\KeyTokenizer;
use Respect\Validation\Validator as V;

class ClientController extends Controller
{

	public function index($request, $response)
	{
		$user = $this->auth->requestUser($request);

		if (!$user) {
			return $response->withJson([
				'status' => false,
				'errors' => 'User not found!'
			], 422);
		}

		$clients = Client::where('user_id', $user->id)->get();

		$transform = new Collection($clients, new ClientTransformer());
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson([
			'status' => true,
			'clients' => $data,
		]);
	}
	
	public function store($request, $response)
	{
		$user = $this->auth->requestUser($request);

		if (!$user) {
			return $response->withJson([
				'status' => false,
				'errors' => 'User not found!'
			], 422);
		}

		$validation = $this->validateClientRequest($request);

    if ($validation->failed()) {
      return $response->withJson([
      	'status' => false,
      	'errors' => $validation->getErrors()
      ], 422);
    }

    $key = (new KeyTokenizer(64))->generate();
    //return $response->withJson(["key" => $key]);

    $client = Client::create([
    	'user_id' => $user->id,
			'name' => $request->getParam('name'),
			'email' => $request->getParam('email'),
			'app_key' => $key,
		]);

		$transform = new Item($client, new ClientTransformer());
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson([
			'status' => true,
			'client' => $data,
		]);
	}

	protected function validateClientRequest($request)
	{
		return $this->validator->validate($request,
      [
        'name' => v::noWhitespace()->notEmpty(),
        'email' => v::noWhitespace()->notEmpty(),
      ]);

	}
}
