<?php

namespace App\Controllers;

use App\Models\Review;
use App\Controllers\Controller;
use League\Fractal\Resource\Item;
use App\Services\KeyTokenizer;
use Respect\Validation\Validator as V;
use Carbon\Carbon;

class ReviewController extends Controller
{
	
		public function store($request, $response)
	{
		$user = $this->auth->requestUser($request);

		if (!$user) {
			return $response->withJson([
				'status' => false,
				'errors' => 'User not found!'
			]);
		}

		$validation = $this->validateReviewRequest($request);

    if ($validation->failed()) {
      return $response->withJson([
      	'status' => false,
      	'errors' => $validation->getErrors()
      ], 422);
    }

   
    $review = Review::create([
    	    'user_id' => $user->id,
			'name' => $request->getParam('content'),
			'status' => 0,
			'publish_date' => Carbon::now(),
		]);

		$transform = new Item($client, new ReviewTransformer());
		$data = $this->fractal->createData($transform)->toArray();

		return $response->withJson([
			'status' => true,
			'review' => $data,
		]);
	}

	protected function validateReviewRequest($request)
	{
		return $this->validator->validate($request,
      [
        'content' => v::noWhitespace()->notEmpty(),
      ]);
	}
}