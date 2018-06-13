<?php

namespace App\Controllers\User;

use App\Models\User;
use App\Controllers\Controller;

class ProfileController extends Controller
{
	
	public function show($request, $response, array $args)
	{
		$user = User::where('username', $args['username'])->firstOrFail();
		$requestUser = $this->auth->requestUser($request);
		$followingStatus = false;

		if ($requestUser) {
			$followingStatus = $requestUser->isFollowing($user->id);
		}
		
		return $response->withJson([
			'profile' => [
				'name' => $user->name,
				'username' => $user->username,
				'bio' => $user->bio,
				'following' => $followingStatus,
			],
		]);
	}

	public function follow($request, $response, array $args)
	{
		$requestUser = $this->auth->requestUser($request);

		if (!$requestUser) {
			return $response->withJson(['message' => 'An error occured'])->withStatus(422);
		}

		$user = User::where('username', $args['username'])->firstOrFail();

		$requestUser->follow($user->id);

		return $response->withJson([
			'profile' => [
				'name' => $user->name,
				'username' => $user->username,
				'bio' => $user->bio,
			],
		]);
	}

	public function unfollow($request, $response, array $args)
	{
		$requestUser = $this->auth->requestUser($request);

		if (!$requestUser) {
			return $response->withJson(['message' => 'An error occured'])->withStatus(422);
		}

		$user = User::where('username', $args['username'])->firstOrFail();

		$requestUser->unfollow($user->id);

		return $response->withJson([
			'profile' => [
				'name' => $user->name,
				'username' => $user->username,
				'bio' => $user->bio,
			],
		]);
	}
}