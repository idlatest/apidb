<?php

namespace App\Auth\User;

use App\Models\User;
use DateTime;
use Firebase\JWT\JWT;

class Auth {

	protected $appConfig;

	const IDENTIFIER = 'username';

	public function __construct($appConfig) {
		$this->appConfig = $appConfig;
	}

	public function generateToken(User $user) {
		$start = new DateTime();
		$expires = new DateTime("now +1 year");

		$payload = [
			"iat" => $start->getTimeStamp(),
      "exp" => $expires->getTimeStamp(),
      "jti" => base64_encode(random_bytes(16)),
      'iss' => $this->appConfig['app']['url'],  // Issuer
      "sub" => $user->{ self::IDENTIFIER },
		];

		$secret = $this->appConfig['jwt']['secret'];
    $token = JWT::encode($payload, $secret, "HS256");

    return $token;
	}

	public function attempt($username, $password) {
		$user = User::where(self::IDENTIFIER, $username)->orWhere('email', $username)->first();

		if (!$user) {
			return false;
		}

		if (password_verify($password, $user->password)) {
			return $user;
		}

		return false;
	}

	public function requestUser($request)
	{
		if ($token = $request->getAttribute('token')) {
			return User::where(self::IDENTIFIER, $token['sub'])->first();
		}
	}
}
