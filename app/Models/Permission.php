<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * 
 * @property int $idPerm
 * @property string $libPerm
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permission';
	protected $primaryKey = 'idPerm';
	public $timestamps = false;

	protected $fillable = [
		'libPerm'
	];
}
