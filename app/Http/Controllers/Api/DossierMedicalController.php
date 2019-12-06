<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\PersonnalErrors;
use App\Http\Requests\DossierMedicalRequest;
use App\Models\DossierMedical;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Netpok\Database\Support\DeleteRestrictionException;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DossierMedicalController extends Controller
{
    use PersonnalErrors;
    protected $table = 'dossier_medicals';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dossiers = DossierMedical::with([
            'allergies'=> function ($query) {
                $query->orderBy('date', 'desc');
            },
            'antecedents',
            'patient',
            'patient.user',
            'consultationsMedecine',
            'consultationsObstetrique',
            'traitements'=> function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();
        foreach ($dossiers as $dossier){
            if (!is_null($dossier)){
                $dossier->updateDossier();
            }
        }
        return response()->json(['dossiers'=>$dossiers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DossierMedicalRequest $request)
    {

        $patient =Patient::with('dossier')->find($request->get('patient_id'));
        if (!is_null($patient->dossier) or !empty($patient->dossier)){
            $this->revealDuplicateDossier($patient->dossier->numero_dossier);
        }

        $numero_dossier = $this->randomNumeroDossier();
        $dossier = DossierMedical::create([
            'patient_id'=>$request->get('patient_id'),
            "date_de_creation"=>Carbon::now()->format('Y-m-d'),
            "numero_dossier"=>$numero_dossier,
        ]);

        defineAsAuthor("DossierMedical",$dossier->id,'create',$dossier->patient->user_id);

        return response()->json(['dossier'=>$dossier]);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($slug)
    {
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $user = Auth::user();
        $userRoles = $user->getRoleNames();
        if(gettype($userRoles->search('Souscripteur')) == 'integer'){
            $dossier = DossierMedical::with([
                'allergies'=> function ($query) {
                    $query->orderBy('date', 'desc');
                },
                'antecedents',
                'patient',
                'patient.user',
                'patient.souscripteur.user',
                'consultationsMedecine',
                'consultationsObstetrique',
                'consultationsObstetrique.echographies',
//                'hospitalisations'=> function ($query) {
//                    $query->orderBy('created_at', 'desc');
//                },
                'traitements'=> function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'resultatsImagerie',
                'resultatsLabo'
            ])->whereSlug($slug)->first();
            return response()->json($dossier,419);
        }else{

            $dossier = DossierMedical::with([
                'allergies'=> function ($query) {
                    $query->orderBy('date', 'desc');
                },
                'antecedents',
                'patient',
                'patient.user',
                'patient.souscripteur.user',
                'consultationsMedecine',
                'consultationsObstetrique',
                'consultationsObstetrique.echographies',
                'hospitalisations'=> function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'traitements'=> function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'resultatsImagerie',
                'resultatsLabo'
            ])->whereSlug($slug)->first();

            if (!is_null($dossier)) {
                $dossier->updateDossier();
            }

            return response()->json(['dossier'=>$dossier]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($slug)
    {
        $this->validatedSlug($slug,$this->table);

        try{
            $dossier = DossierMedical::with([
                'allergies'=> function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'patient',
                'consultationsMedecine',
                'consultationsObstetrique'])->whereSlug($slug)->first();
            $dossier->delete();
            $patient = Patient::where('user_id','=',$dossier->patient_id)->first();
            $patient->delete();

            defineAsAuthor("DossierMedical",$dossier->id,'delete',$dossier->patient->user_id);
            defineAsAuthor("Patient",$dossier->id,'delete',$dossier->patient->user_id);

            return response()->json(['dossier'=>$dossier]);
        }catch (DeleteRestrictionException $deleteRestrictionException){
            $this->revealError('deletingError',$deleteRestrictionException->getMessage());
        }
    }


    public static function randomNumeroDossier(){
        $resultat = ''.rand(0,100000000);
        while (strlen($resultat)<8){
            $longueur = strlen($resultat);
            if ($longueur == 1)
                $resultat = $resultat.''.rand(0,10000000);
            elseif ($longueur == 2 )
                $resultat = $resultat.''.rand(0,1000000);
            elseif ($longueur == 3 )
                $resultat = $resultat.''.rand(0,100000);
            elseif ($longueur == 4 )
                $resultat = $resultat.''.rand(0,10000);
            elseif ($longueur == 5 )
                $resultat = $resultat.''.rand(0,1000);
            elseif ($longueur == 6 )
                $resultat = $resultat.''.rand(0,100);
            elseif ($longueur == 7 )
                $resultat = $resultat.''.rand(0,10);

        }

        while(count(DossierMedical::where('numero_dossier','=',$resultat)->get())>0){
            $resultat = self::randomNumeroDossier();
        }

        return $resultat;
    }

    public static function genererDossier($patientId){
        $numero_dossier = self::randomNumeroDossier();
        $dossier = DossierMedical::create([
            'patient_id'=>$patientId,
            "date_de_creation"=>Carbon::now()->format('Y-m-d'),
            "numero_dossier"=>$numero_dossier,
        ]);

        return response()->json(['dossier'=>$dossier]);
    }


    public function dossierByPatientId($patient_id){
        $validator = Validator::make(compact('patient_id'),'exists:dossier_medicals,patient_id');
        if ($validator->fails()){
            return response()->json(compact($validator->errors()->getMessages()),422);
        }

        $dossier = DossierMedical::with([
            'allergies'=> function ($query) {
                $query->orderBy('date', 'desc');
            },
            'antecedents',
            'patient',
            'consultationsMedecine',
            'consultationsObstetrique',
            'consultationsObstetrique.echographies',
            'hospitalisations'=> function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'traitements'=> function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'resultatsImagerie',
            'resultatsLabo'
        ])->where('patient_id' ,'=',$patient_id)->first();

        if (!is_null($dossier)) {
            $dossier->updateDossier();
        }

        return response()->json(['dossier'=>$dossier]);
    }
}
