<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lieu
 * 
 * @property int $idLieu
 * @property string $libelleLieu
 * 
 * @property Collection|Activite[] $activites
 *
 * @package App\Models
 */
class Lieu extends Model
{
	protected $table = 'lieu';
	protected $primaryKey = 'idLieu';
	public $timestamps = false;

	protected $fillable = [
		'libelleLieu'
	];

	public function activites()
	{
		return $this->hasMany(Activite::class, 'lieu');
	}
}
