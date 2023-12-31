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
 * App\Models\SpecialiteSuivi
 *
 * @property int $id
 * @property int $suivi_id
 * @property User|null $responsable
 * @property int $specialite_id
 * @property int|null $creator
 * @property string|null $motifs
 * @property string $slug
 * @property string|null $etat
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $createur
 * @property-read mixed $name_and_timestamp
 * @property-read \App\Models\ConsultationType $specialite
 * @property-read \App\Models\Suivi $suivi
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi newQuery()
 * @method static \Illuminate\Database\Query\Builder|SpecialiteSuivi onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi query()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereEtat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereMotifs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereSpecialiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereSuiviId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecialiteSuivi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SpecialiteSuivi withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SpecialiteSuivi withoutTrashed()
 * @mixin \Eloquent
 */
class SpecialiteSuivi extends Model
{
    use SoftDeletes;
    use Sluggable;


    protected $fillable = [
        "suivi_id",
        "specialite_id",
        "responsable",
        "motifs",
        "slug",
        "creator",
        "etat",
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
        SpecialiteSuivi::creating(function ($suivi){
            $suivi->creator = Auth::id();
        });
    }


    public function suivi(){
        return $this->belongsTo(Suivi::class,'suivi_id','id');
    }

    public function responsable(){
        return $this->belongsTo(User::class,'responsable','id');
    }

    public function createur(){
        return $this->belongsTo(User::class,'creator','id');
    }

    public function specialite(){
        return $this->belongsTo(ConsultationType::class,'specialite_id','id');
    }
}
