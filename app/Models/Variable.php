<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Variable
 * 
 * @property string $nomVariable
 * @property int $dataVariable
 *
 * @package App\Models
 */
class Variable extends Model
{
	protected $table = 'variables';
	protected $primaryKey = 'nomVariable';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'dataVariable' => 'int'
	];

	protected $fillable = [
		'dataVariable'
	];
}
