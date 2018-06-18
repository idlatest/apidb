<?php

namespace App\Transformers;

use App\Models\Airport;
use League\Fractal\TransformerAbstract;

class AirportTransformer extends TransformerAbstract
{

	public function transform(Airport $airport)
	{
		return  [
			'code' => $airport->code,
			'name' => $airport->name,
			'cityCode' => $airport->cityCode,
			'cityName' => $airport->cityName,
			'countryName' => $airport->countryName,
			'countryCode' => $airport->countryCode,
			'timezone' => $airport->timezone,
			'latitude' => $airport->lat,
			'longitude' => $airport->lon,
		];
	}
}