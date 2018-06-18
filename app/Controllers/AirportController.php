<?php

namespace App\Controllers;

use App\Models\Airport;
use App\Controllers\Controller;
use App\Transformers\AirportTransformer;
use App\Auth\Client\Auth;
use League\Fractal\{
  Resource\Item,
  Resource\Collection,
  Pagination\IlluminatePaginatorAdapter
};

class AirportController extends Controller
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

		$builder = Airport::query()->latest();
		// $airports = Airport::all();

		if ($country = $request->getParam('country')) {
			$builder->where('countryCode', $country);
		}

		if ($city = $request->getParam('city')) {
			$builder->where('cityCode', $city);
		}

		$airports = $builder->paginate(100);

		$transformer = (new Collection($airports->getCollection(), new AirportTransformer()))
      ->setPaginator(new IlluminatePaginatorAdapter($airports));

    $data = $this->fractal->createData($transformer)->toArray();

		return $response->withJson([
			'status' => true,
			'airports' => $data,
		])->withStatus(200);
	}

	public function show($request, $response, $args)
	{
		$client = $this->clientAuth->requestClient($request);

		if (! $client) {
			return $response->withJson([
				'status' => false,
				'error' => 'Unidentified client!',
			]);
		}
		
		$airport = Airport::where('code', $args['code'])->first();

		$transformer = new Item($airport, new AirportTransformer());

    $data = $this->fractal->createData($transformer)->toArray();

		return $response->withJson([
			'status' => true,
			'airport' => $data,
		])->withStatus(200);
	}
}