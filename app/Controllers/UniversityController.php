<?php

namespace App\Controllers;

use App\Models\University;
use App\Transformers\UniversityTransformer;
use App\Controllers\Controller;

use League\Fractal\{
  Resource\Item,
  Resource\Collection,
  Pagination\IlluminatePaginatorAdapter
};

class UniversityController extends Controller
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

		$builder = University::query()->latest();

		$client->stat()->attach($client->id);

		if ($country = $request->getParam('country')) {
			$builder->where('country_code', $country);
		}

		if ($city = $request->getParam('city')) {
			$builder->where('cityCode', $city);
		}

		if ($type = $request->getParam('type')) {
			$builder->where('type', $type);
		}

		$universities = $builder->paginate(100);

		$transformer = (new Collection($universities->getCollection(), new UniversityTransformer()))
      ->setPaginator(new IlluminatePaginatorAdapter($universities));

    $data = $this->fractal->createData($transformer)->toArray();

		return $response->withJson([
			'status' => true,
			'universities' => $data,
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

		$university = University::find($args['id']);

		$client->stat()->attach($client->id);

		$transformer = (new Item($university, new UniversityTransformer()));

    $data = $this->fractal->createData($transformer)->toArray();

		return $response->withJson([
			'status' => true,
			'university' => $data,
		])->withStatus(200);
	}
}