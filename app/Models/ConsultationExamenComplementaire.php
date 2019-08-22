<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationExamenComplementaire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "consultation_medecine_generale_id",
        "examen_complementaire_id",
    ];
}