<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Animer
 * 
 * @property string $codeType
 * @property int $idActivite
 * @property int $idIntervenant
 * 
 * @property Activite $activite
 * @property Intervenant $intervenant
 *
 * @package App\Models
 */
class Animer extends Model
{
	protected $table = 'animer';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idActivite' => 'int',
		'idIntervenant' => 'int'
	];

	public function activite()
	{
		return $this->belongsTo(Activite::class, 'codeType')
					->where('activite.codeType', '=', 'animer.codeType')
					->where('activite.idActivite', '=', 'animer.idActivite');
	}

	public function intervenant()
	{
		return $this->belongsTo(Intervenant::class, 'idIntervenant');
	}
}
