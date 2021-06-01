<?php

namespace App\Models;

use Carbon\Carbon;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FactureAvis extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        "avis_id",
        "association_id",
        "etablissement_id",
        "dossier_medical_id",
        "slug",
        "creator",
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'NameAndTimestamp'
            ]
        ];
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        //On définit le créateur à la création du suivi
        Suivi::creating(function ($factureAvis){
            $factureAvis->creator = Auth::id();
        });
    }

    public function getNameAndTimestampAttribute() {
        return Str::random(16).' '.Carbon::now()->timestamp;
    }

    public function avis(){
        return $this->belongsTo(Avis::class,'avis_id','id');
    }

    public function association(){
        return $this->belongsTo(Association::class,'association_id','id');
    }

    public function dossier(){
        return $this->belongsTo(DossierMedical::class,'dossier_medical_id','id');
    }

    public function factureDetail(){
        return $this->hasMany(factureAvisDetail::class,'facture_avis_id','id');
    }
    public function createur(){
        return $this->belongsTo(User::class,'creator','id');
    }

    public function files(){
        return $this->morphMany(File::class,'fileable');
    }

    public function etablissement(){
        return $this->belongsTo(EtablissementExercice::class,'etablissement_id','id');
    }
}