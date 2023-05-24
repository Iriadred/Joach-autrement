<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Token
 * 
 * @property int $idUsers
 * @property int $idToken
 * @property string $token
 * @property Carbon|null $dateUtilisation
 * @property string|null $reasonToken
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Token extends Model
{
	protected $table = 'token';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idUsers' => 'int',
		'idToken' => 'int',
		'dateUtilisation' => 'date'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'token',
		'dateUtilisation',
		'reasonToken'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'idUsers');
	}
}
