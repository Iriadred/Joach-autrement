<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 * 
 * @property string $codeType
 * @property string $libType
 * 
 * @property Collection|Activite[] $activites
 *
 * @package App\Models
 */
class Type extends Model
{
	protected $table = 'type';
	protected $primaryKey = 'codeType';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'libType'
	];

	public function activites()
	{
		return $this->hasMany(Activite::class, 'codeType');
	}
}
