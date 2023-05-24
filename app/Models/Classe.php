<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Classe
 * 
 * @property int $idClasse
 * @property string $nomClasse
 *
 * @package App\Models
 */
class Classe extends Model
{
	protected $table = 'classe';
	protected $primaryKey = 'idClasse';
	public $timestamps = false;

	protected $fillable = [
		'nomClasse'
	];
}
