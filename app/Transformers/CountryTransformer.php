<?php

namespace App\Transformers;

use App\Models\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{
	
	public function transform(Country $country)
	{
		return [
			"id" => $country->country_id,
			"code" => $country->code,
			"name" => $country->name,
			"fullName" => $country->full_name,
			"iso3" => $country->iso3,
			"number" => $country->number,
			"continentCode" => $country->continent_code,
		];
	}
}