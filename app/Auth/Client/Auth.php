<?php

namespace App\Auth\Client;

use App\Models\Client;
use DateTime;
use Firebase\JWT\JWT;

class Auth {

	protected $appConfig;

	const IDENTIFIER = 'id';

	public function __construct($appConfig) {
		$this->appConfig = $appConfig;
	}

	public function generateToken(Client $client) {
		$start = new DateTime();
		$expires = new DateTime("now +1 year");

		$payload = [
			"iat" => $start->getTimeStamp(),
      "exp" => $expires->getTimeStamp(),
      "jti" => base64_encode(random_bytes(16)),
      'iss' => $this->appConfig['app']['url'],  // Issuer
      "sub" => $client->{ self::IDENTIFIER },
		];

		$secret = $this->appConfig['jwt']['secret'];
    $token = JWT::encode($payload, $secret, "HS256");

    return $token;
	}

	public function requestClient($request)
	{
		if ($token = $request->getAttribute('token')) {
			return Client::where(self::IDENTIFIER, $token['sub'])->first();
		}
	}
}
