<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	
	protected $table = 'apidb_clients';

	protected $fillable = [
		'id',
		'user_id',
		'name',
		'email',
		'domain',
		'icon_name',
		'policy_url',
		'app_key',
	];

	public function stat()
	{
		return $this->belongsToMany(Client::class, 'apidb_request_statistics')->withTimestamps();
	}
}