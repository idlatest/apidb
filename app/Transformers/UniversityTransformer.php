<?php

namespace App\Transformers;

use App\Models\University;
use League\Fractal\TransformerAbstract;

class UniversityTransformer extends TransformerAbstract
{
	
	public function transform(University $university)
	{
		return [
			"id" => $university->id,
			"countryCode" => $university->country_code,
			"name" => $university->name,
			"bio" => $university->bio,
			"code" => $university->code,
			"type" => $university->type,
			"establishDate" => $university->establish_date,
		];
	}
}