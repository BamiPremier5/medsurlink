<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    ];

    protected $hidden = [
        'creator',
        'deleted_at',
        'updated_at'
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
}
