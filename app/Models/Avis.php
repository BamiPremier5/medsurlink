<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * App\Models\Avis
 *
 * @property int $id
 * @property int $dossier_medical_id
 * @property string|null $objet
 * @property string|null $slug
 * @property string $description
 * @property int|null $creator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $creer_lien
 * @property string $code_urgence
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultationFichier[] $consultationFichier
 * @property-read int|null $consultation_fichier_count
 * @property-read User|null $createur
 * @property-read \App\Models\DossierMedical $dossier
 * @property-read mixed $name_and_timestamp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MedecinAvis[] $medecinAvis
 * @property-read int|null $medecin_avis_count
 * @method static \Illuminate\Database\Eloquent\Builder|Avis findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Avis newQuery()
 * @method static \Illuminate\Database\Query\Builder|Avis onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Avis query()
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereCodeUrgence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereCreerLien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereDossierMedicalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereObjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Avis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Avis withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Avis withoutTrashed()
 * @mixin \Eloquent
 */
class Avis extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        "dossier_medical_id",
        "objet",
        "description",
        "slug",
        "creer_lien",
        "creator",
        "code_urgence",
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function scopeAvisSemaineMoisAnnee($query, $intervalle_debut, $intervalle_fin)
    {
        return $query->where(function ($query) use($intervalle_debut, $intervalle_fin) {
            $query->whereDate('created_at', '>=', $intervalle_debut)->whereDate('created_at', '<=', $intervalle_fin);
        })->orderBy('created_at', 'asc');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'NameAndTimestamp'
            ]
        ];
    }

    public function getNameAndTimestampAttribute() {
        return Str::random(16).' '.Carbon::now()->timestamp;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        //On définit le créateur à la création du suivi
        Avis::creating(function ($avis){
            $avis->creator = Auth::id();
        });
    }

    public function createur(){
        return $this->belongsTo(User::class,'creator','id');
    }

    public function dossier(){
        return $this->belongsTo(DossierMedical::class,'dossier_medical_id','id');
    }

    public function medecinAvis(){
        return $this->hasMany(MedecinAvis::class,'avis_id','id');
    }

    public function consultationFichier(){
        return $this->hasMany(ConsultationFichier::class,'dossier_medical_id','dossier_medical_id');
    }
}
