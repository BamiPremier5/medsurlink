<?php

namespace App\Http\Controllers\Api;

use App\Models\ConsultationMedecineGenerale;
use App\Models\Motif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConsultationMotifController extends Controller
{
    public function removeMotif(Request $request){
        $validation = Validator::make($request->all(),[
            "consultation"=>"required|integer|exists:consultation_medecine_generales,id",
            "motifs.*"=>"required|integer|exists:motifs,id"
        ]);

        if ($validation->fails()){
            return response()->json(['error'=>$validation->errors()],419);
        }

        $consultation = ConsultationMedecineGenerale::find($request->get('consultation'));
        $consultation->motifs()->detach($request->get('motifs'));

        $consultation = ConsultationMedecineGenerale::with('motifs')->find($request->get('consultation'));
        return response()->json(['consultation'=>$consultation]);
    }

    public function ajouterMotif(Request $request){
        $validation = Validator::make($request->all(),[
            "consultation"=>"required|integer|exists:consultation_medecine_generales,id",
            "motifs.*.id"=>"sometimes|integer|exists:motifs,id",
            "motifsACreer.*.reference"=>"sometimes|string|min:2",
            "motifsACreer.*.description"=>"sometimes|string|min:2",
        ]);

        if ($validation->fails()){
            return response()->json(['error'=>$validation->errors()],419);
        }

        $consultation = ConsultationMedecineGenerale::find($request->get('consultation'));
        if (!is_null($request->get('motif'))){
            $consultation->motifs()->attach($request->get('motif'));

        }

        $motifs = $request->get('motifsACreer');
        if (!is_null($motifs) or !empty($motifs)){
            foreach ( $motifs as $motif)
            {
                $motifCreer = Motif::create([
                    'reference'=>$motif['reference'],
                    'description'=>$motif['description']
                ]);

                $consultation->motifs()->attach($motifCreer->id);
            }
        }
        $consultation = ConsultationMedecineGenerale::with('motifs')->find($request->get('consultation'));
        return response()->json(['consultation'=>$consultation]);
    }
}