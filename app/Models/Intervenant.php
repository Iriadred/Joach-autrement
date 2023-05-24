<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Intervenant
 * 
 * @property int $idIntervenant
 * @property string $nomIntervenant
 * @property string $prenomIntervenant
 * @property string $professionIntervenant
 * @property int $isExt
 * 
 * @property Collection|Animer[] $animers
 *
 * @package App\Models
 */
class Intervenant extends Model
{
	protected $table = 'intervenant';
	protected $primaryKey = 'idIntervenant';
	public $timestamps = false;

	protected $casts = [
		'isExt' => 'int'
	];

	protected $fillable = [
		'nomIntervenant',
		'prenomIntervenant',
		'professionIntervenant',
		'isExt'
	];

	public function animers()
	{
		return $this->hasMany(Animer::class, 'idIntervenant');
	}
}
