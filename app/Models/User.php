<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Class User
 * 
 * @property int $idUsers
 * @property int|null $idClasse
 * @property int $idPerm
 * @property string $nomUsers
 * @property string|null $emailUsers
 * @property string|null $mdpUsers
 * @property int $premiereConnexion
 * @property int $validationMail
 * @property int $inscriptionEnd
 * 
 * @property Classe|null $classe
 * @property Permission $permission
 * @property Collection|Inscrire[] $inscrires
 * @property Token $token
 *
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract
{
	use Authenticatable;
	protected $table = 'users';
	protected $primaryKey = 'idUsers';
	public $timestamps = false;

	protected $casts = [
		'idClasse' => 'int',
		'idPerm' => 'int',
		'premiereConnexion' => 'int',
		'validationMail' => 'int',
		'inscriptionEnd' => 'int'
	];

	protected $fillable = [
		'idUsers',
		'idClasse',
		'idPerm',
		'nomUsers',
		'emailUsers',
		'mdpUsers',
		'premiereConnexion',
		'validationMail',
		'inscriptionEnd'
	];

	public function classe()
	{
		return $this->belongsTo(Classe::class, 'idClasse');
	}

	public function permission()
	{
		return $this->belongsTo(Permission::class, 'idPerm');
	}

	public function inscrires()
	{
		return $this->hasMany(Inscrire::class, 'idUsers');
	}

	public function token()
	{
		return $this->hasOne(Token::class, 'idUsers');
	}

	/**
     * Retourne le mot de passe de l'utilisateur
     */
    public function getAuthPassword()
    {
        return $this->mdpUsers;
    }

    /**
     * Retourne l'identifiant de l'utilisateur
     */
    public function getAuthIdentifier()
    {
        return $this->idUsers;
    }

    /**
     * Retourne le nom de l'identifiant de l'utilisateur
     */
    public function getAuthIdentifierName()
    {
        return 'idUsers';
    }
}