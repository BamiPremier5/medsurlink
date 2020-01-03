<?php

namespace App\Models;

use App\Models\Traits\SlugRoutable;
use App\Scopes\RestrictDossierScope;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Netpok\Database\Support\RestrictSoftDeletes;

class ConsultationObstetrique extends Model
{
    use SoftDeletes;
    use RestrictSoftDeletes;
    use Sluggable;
    use SluggableScopeHelpers;
    use SlugRoutable;

    /**
     * The relations restricting model deletion
     */
    protected $restrictDeletes = ['consultationPrenatales','echographies'];

    protected $fillable = [
        "dossier_medical_id",
        "date_creation",
        "numero_grossesse",
        "ddr",
        "serologie",
        "groupe_sanguin",
        "statut_socio_familiale",
        "assuetudes",
        "antecassuetudesedent_de_transfusion",
        "facteur_de_risque",
        "antecedent_conjoint",
        'archieved_at',
        'passed_at',
        'slug',
        "pcr_gonocoque",
        "pcr_chlamydia",
        "rcc",
        "glycemie",
        "emu",
        "tsh",
        "anti_tpo",
        "ft4",
        "ft3",
        "attention",
        "info_prise_en_charge",
        "etablissement_id",
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'DossierAndTimestamp'
            ]
        ];
    }
    public function getDossierAndTimestampAttribute() {
        return $this->dossier->slug . ' ' .Carbon::now()->timestamp;
    }
    public function consultationPrenatales(){
        return $this->hasMany(ConsultationPrenatale::class,'consultation_obstetrique_id','id');
    }
    public function echographies(){
        return $this->hasMany(Echographie::class,'consultation_obstetrique_id','id');
    }

    public function dossier(){
        return $this->belongsTo(DossierMedical::class,'dossier_medical_id','id');
    }
    public function etablissement(){
        return $this->belongsTo(EtablissementExercice::class,'etablissement_id','id');
    }
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RestrictDossierScope);
    }

    public function updateObstetricConsultation(){
        $user = $this->dossier->patient->user;
        $allergies = $this->dossier->allergies;
        foreach ($allergies as $allergy)
        {
            $allergieIsAuthor = checkIfIsAuthorOrIsAuthorized("Allergie",$allergy->id,"create");
            $allergy['isAuthor'] = $allergieIsAuthor->getOriginalContent();
        }
        $this['allergies']= $allergies;
        $this['user']=$user;
        $isAuthor = checkIfIsAuthorOrIsAuthorized("ConsultationObstetrique",$this->id,"create");
        $this['isAuthor']=$isAuthor->getOriginalContent();
    }

    public function scopeOrderByDateDeRendezVous($query)
    {
        return $query->orderBy('ddr', 'desc');
    }
}
