<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	
	public function transform(User $user)
	{
		return [
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'username' => $user->username,
			'email' => $user->email,
			'avatar' => $user->image,
		];
	}
}