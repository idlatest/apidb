<?php

namespace App\Transformers;

use App\Models\Client;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
	
	public function transform(Client $client)
	{
		return [
			'id' => $client->id,
			'name' => $client->name,
			'email' => $client->email,
			'domain' => $client->domain,
			'icon' => $client->icon_name,
			'policyUrl' => $client->policy_url,
			'key' => $client->app_key,
		];
	}
}