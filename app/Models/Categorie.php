<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categorie
 * 
 * @property int $idCategorie
 * @property string $libCategorie
 * 
 * @property Collection|Activite[] $activites
 *
 * @package App\Models
 */
class Categorie extends Model
{
	protected $table = 'categorie';
	protected $primaryKey = 'idCategorie';
	public $timestamps = false;

	protected $fillable = [
		'libCategorie'
	];

	public function activites()
	{
		return $this->hasMany(Activite::class, 'idCategorie');
	}
}
