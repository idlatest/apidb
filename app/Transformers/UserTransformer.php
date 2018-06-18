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
			'phone' => $user->phone,
			'accountType' => $user->account_type,
			'country' => $user->country,
			'city' => $user->city,
			'website' => $user->website,
			'description' => $user->description,
			'private' => $user->private,
			'suspended' => $user->suspended,
			'cover' => $user->cover,
			'gender' => $user->gender,
			'online' => $user->online,
			'offline' => $user->offline,
			'location' => $user->location,
			'companyName' => $user->company_name,
			'companyDesc' => $user->company_desc,
			'token' => $user->token,
		];
	}
}