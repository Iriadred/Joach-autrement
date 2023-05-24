<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inscrire
 * 
 * @property int $idUsers
 * @property string $codeType
 * @property int $idActivite
 * 
 * @property Activite $activite
 * @property User $user
 *
 * @package App\Models
 */
class Inscrire extends Model
{
	protected $table = 'inscrire';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idUsers' => 'int',
		'idActivite' => 'int'
	];

	public function activite()
	{
		return $this->belongsTo(Activite::class, 'codeType')
					->where('activite.codeType', '=', 'inscrire.codeType')
					->where('activite.idActivite', '=', 'inscrire.idActivite');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'idUsers');
	}
}
