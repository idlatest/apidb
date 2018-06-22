<?php

namespace App\Controllers;

use App\Models\Country;
use App\Controllers\Controller;
use App\Transformers\CountryTransformer;
use League\Fractal\{
  Resource\Item,
  Resource\Collection,
  Pagination\IlluminatePaginatorAdapter
};

class CountryController extends Controller
{
	
	public function index($request, $response)
	{
		$client = $this->clientAuth->requestClient($request);

		if (! $client) {
			return $response->withJson([
				'status' => false,
				'error' => 'Unidentified client!',
			]);
		}

		$builder = Country::query();

		$client->stat()->attach($client->id);

		$countries = $builder->paginate(100);

		$transform = (new Collection($countries->getCollection(), new CountryTransformer()))
			->setPaginator(new IlluminatePaginatorAdapter($countries));
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson([
			'status' => true,
			'countries' => $data,
		], 200);
	}
}