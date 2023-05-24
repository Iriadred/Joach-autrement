<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activite
 * 
 * @property string $codeType
 * @property int $idActivite
 * @property int $idCategorie
 * @property bool $momentActivite
 * @property int $dateActivite
 * @property int $heureDebutActivite
 * @property int $heureFinActivite
 * @property string $titreActivite
 * @property string $lieu
 * @property int $nbPlaceActivite
 * @property string $descriptionActivite
 * @property string $enteteActivite
 * @property string $publicActivite
 * @property bool $activiteAnnule
 * 
 * @property Categorie $categorie
 * @property Type $type
 * @property Collection|Animer[] $animers
 * @property Collection|Inscrire[] $inscrires
 *
 * @package App\Models
 */
class Activite extends Model
{
	protected $table = 'activite';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idActivite' => 'int',
		'idCategorie' => 'int',
		'momentActivite' => 'bool',
		'dateActivite' => 'int',
		'heureDebutActivite' => 'int',
		'heureFinActivite' => 'int',
		'nbPlaceActivite' => 'int',
		'activiteAnnule' => 'bool'
	];

	protected $fillable = [
		'idCategorie',
		'momentActivite',
		'dateActivite',
		'heureDebutActivite',
		'heureFinActivite',
		'titreActivite',
		'lieu',
		'nbPlaceActivite',
		'descriptionActivite',
		'enteteActivite',
		'publicActivite',
		'activiteAnnule'
	];

	public function categorie()
	{
		return $this->belongsTo(Categorie::class, 'idCategorie');
	}

	public function type()
	{
		return $this->belongsTo(Type::class, 'codeType');
	}

	public function animers()
	{
		return $this->hasMany(Animer::class, 'codeType');
	}

	public function inscrires()
	{
		return $this->hasMany(Inscrire::class, 'codeType');
	}
}
