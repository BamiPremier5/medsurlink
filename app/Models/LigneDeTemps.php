<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\ActivitesControle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\LigneDeTemps
 *
 * @property int $id
 * @property int $dossier_medical_id
 * @property int $etat
 * @property int $motif_consultation_id
 * @property string $date_consultation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $affiliation_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ActiviteAmaPatient[] $activites_ama_patients
 * @property-read int|null $activites_ama_patients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ActivitesControle[] $activites_referent_patients
 * @property-read int|null $activites_referent_patients_count
 * @property-read \App\Models\Affiliation|null $affiliation
 * @property-read \App\Models\Cardiologie $cardiologie
 * @property-read \App\Models\Cloture|null $cloture
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultationMedecineGenerale[] $consultationGeneral
 * @property-read int|null $consultation_general_count
 * @property-read \App\Models\ConsultationObstetrique $consultationObstetrique
 * @property-read \App\Models\DossierMedical $dossier
 * @property-read \App\Models\Kinesitherapie $kenesitherapie
 * @property-read \App\Models\Motif $motif
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Motif[] $motifs
 * @property-read int|null $motifs_count
 * @property-read \App\Models\PrescriptionValidation $prescriptionValidation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RendezVous[] $rendezVous
 * @property-read int|null $rendez_vous_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultationExamenValidation[] $validations
 * @property-read int|null $validations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps newQuery()
 * @method static \Illuminate\Database\Query\Builder|LigneDeTemps onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps query()
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereAffiliationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereDateConsultation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereDossierMedicalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereMotifConsultationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LigneDeTemps whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LigneDeTemps withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LigneDeTemps withoutTrashed()
 * @mixin \Eloquent
 */
class LigneDeTemps extends Model
{
    use SoftDeletes;

    protected $table = 'ligne_de_temps';

    protected $fillable = [
        'dossier_medical_id',
        'motif_consultation_id',
        'etat',
        'date_consultation',
        'affiliation_id',
        'creator',
    ];

    protected $appends = ['description'];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        LigneDeTemps::creating(function ($user) {

            $user->creator = Auth::id();
        });
    }

    public function getDescriptionAttribute()
    {
        if ($this->created_at !== null) {
            return "{$this->motif->description} ({$this->created_at->format('d-m-Y')})";
        } else {
            return "{$this->motif->description}";
        }
    }

    // dossier médicaux lié à la ligne de temps
    public function dossier()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id', 'id');
    }
    public function  motif()
    {
        return $this->belongsTo(Motif::class, 'motif_consultation_id', 'id');
    }
    public function  kenesitherapie()
    {
        return $this->belongsTo(Kinesitherapie::class, 'ligne_de_temps_id', 'id');
    }
    public function  consultationGeneral()
    {
        return $this->hasMany(ConsultationMedecineGenerale::class, 'ligne_de_temps_id', 'id');
    }
    public function  cardiologie()
    {
        return $this->belongsTo(Cardiologie::class, 'ligne_de_temps_id', 'id');
    }
    public function  consultationObstetrique()
    {
        return $this->belongsTo(ConsultationObstetrique::class, 'ligne_de_temps_id', 'id');
    }
    public function  prescriptionValidation()
    {
        return $this->belongsTo(PrescriptionValidation::class, 'ligne_de_temps_id', 'id');
    }
    public function  validations()
    {
        return $this->hasMany(ConsultationExamenValidation::class, 'ligne_de_temps_id', 'id');
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class, 'ligne_temps_id');
    }

    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }


    public function activites_ama_patients()
    {
        return $this->hasMany(ActiviteAmaPatient::class, 'ligne_temps_id');
    }

    public function activites_referent_patients()
    {
        return $this->hasMany(ActivitesControle::class, 'ligne_temps_id');
    }

    public function motifs()
    {
        return $this->morphToMany(Motif::class, 'motiffable');
    }

    public function cloture()
    {
        return $this->morphOne(Cloture::class, 'cloturable');
    }
}
