<?php

namespace App\Controllers;

use App\Models\Category;
use App\Controllers\Controller;
use App\Transformers\CategoryTransformer;
use League\Fractal\Resource\Collection;


class CategoryController extends Controller
{
	
	public function index($request, $response)
	{
		$categories = Category::where('cat_status', '1')->get();

		$transform = new Collection($categories, new CategoryTransformer);
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson($data);
	}
}