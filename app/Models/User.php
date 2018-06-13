<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
	protected $table = 'apidb_users';

	protected $fillable = [
		'first_name',
		'last_name',
		'username',
		'email',
		'account_type',
		'password',
	];
}