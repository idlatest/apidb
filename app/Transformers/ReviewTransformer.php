<?php

namespace App\Transformers;

use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{

	public function transform(Review $review)
	{
		return  [
			'content' => $review->content,
		];
	}
}