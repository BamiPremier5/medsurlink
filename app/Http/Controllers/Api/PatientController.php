<?php

namespace App\Http\Controllers\Api;

use App\User;
use Carbon\Carbon;
use App\Models\Suivi;
use App\Models\Patient;
use App\Traits\SmsTrait;
use App\Models\RendezVous;
use App\Mail\updateSetting;
use App\Models\Affiliation;
use App\Models\LigneDeTemps;
use App\Models\Souscripteur;
use Illuminate\Http\Request;
use App\Models\DossierMedical;
use App\Models\ReponseSecrete;
use App\Mail\PatientAffiliated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\EtablissementExercice;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\patientStoreRequest;
use App\Http\Requests\PatientUpdateRequest;

use App\Models\EtablissementExercicePatient;
use App\Http\Controllers\Traits\PersonnalErrors;
use App\Models\ConsultationMedecineGenerale;
use Netpok\Database\Support\DeleteRestrictionException;

class PatientController extends Controller
{
    use PersonnalErrors;
    use SmsTrait;
    protected $table = 'patients';
    /**
     * @OA\Get(
     *      path="/patient",
     *      operationId="getPatientList",
     *      tags={"Patient"},
     *      security={
     *       {"passport": {}},
     *       },
     *      summary="Get list of patient",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::with(['souscripteur', 'dossier', 'user', 'affiliations', 'financeurs.financable', 'medecinReferent.medecinControles.user'])->restrictUser()->latest()->get();
        return response()->json(['patients' => $patients]);
    }

    public function ListingPatientSansIntervention($date)
    {

        $today = Carbon::now()->format('Y-m-d');
        $date = Carbon::now()->subDays($date + 1)->format('Y-m-d');

        $patients = Patient::WhereDoesntHave('rendezVous', function ($query) use ($date, $today) {
            $query->RdvSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.consultationsMedecine', function ($query) use ($date, $today) {
            $query->ConsultationSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.resultatsLabo', function ($query) use ($date, $today) {
            $query->ResultLaboSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.resultatsImagerie', function ($query) use ($date, $today) {
            $query->ResultImagerieSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.hospitalisations', function ($query) use ($date, $today) {
            $query->HospitalisationSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.consultationsManuscrites', function ($query) use ($date, $today) {
            $query->ConsultationFichierSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.comptesRenduOperatoire', function ($query) use ($date, $today) {
            $query->CompteRenduSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.avis', function ($query) use ($date, $today) {
            $query->AvisSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.kinesitherapies', function ($query) use ($date, $today) {
            $query->KinesiSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.antecedents', function ($query) use ($date, $today) {
            $query->AntecedentsSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('dossier.traitements', function ($query) use ($date, $today) {
            $query->TraitementSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('medecinReferent', function ($query) use ($date, $today) {
            $query->MedRefSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('activitesAma', function ($query) use ($date, $today) {
            $query->ActiviteAmaSemaineMoisAnnee($date, $today);
        })->WhereDoesntHave('activitesMedecinReferent', function ($query) use ($date, $today) {
            $query->ActiviteMedControleMoisAnnee($date, $today);
        })->with(['user'])->get();

        // scopeActiviteMedControleMoisAnnee



        // ->whereHas('dossier')


        // $rdvs = RendezVous::with(['patient','praticien','sourceable','initiateur'])->has('patient')
        // ->where(function ($query) use($userId) {
        //     $query->where('praticien_id', $userId)->orWhere('patient_id', $userId)->orWhere('initiateur', $userId);
        // })->Jours306090($date_debut, $date_fin)->get();

        // $today = Carbon::now()->format('Y-m-d');
        // $date = Carbon::now()->subDays($date+1)->format('Y-m-d');
        // $metriques = Patient::semaineMoisAnnee($date, $today)->get();

        return response()->json(['patients' => $patients]);
    }

    public function listingPatients($patient_search)
    {
        $patients = Patient::whereHas('user', function ($query) use ($patient_search) {
            $query->where('nom', 'like',  '%' . $patient_search . '%')
                ->orwhere('prenom', 'like',  '%' . $patient_search . '%')
                ->orwhere('email', 'like',  '%' . $patient_search . '%')
                ->orwhere(DB::raw('CONCAT_WS(" ", nom, prenom)'), 'like',  '%' . $patient_search . '%')
                ->orwhere(DB::raw('CONCAT_WS(" ", prenom, nom)'), 'like',  '%' . $patient_search . '%');
        })->with(['user:id,nom,prenom,email'])->select('user_id')->get();
        return response()->json(['patients' => $patients]);
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function medicasureStorePatient(Request $request)
    {
        $data = (object) $request->json()->all();
        Log::info(json_encode($data->original['cim']));
        $cim = (object) $data->original['cim'];
        $cim = (object) $cim->original['cim'];
        Log::alert(json_encode($cim->nomSouscripteur));
        $souscripteurUser = new User();
        $souscripteurUser->nom = $cim->nomSouscripteur;
        $souscripteurUser->prenom = $cim->prenomSouscripteur;
        $souscripteurUser->email = $cim->emailSouscripteur1;
        $souscripteurUser->nationalite = $cim->paysResidenceSouscripteur;
        $souscripteurUser->ville = $cim->villeResidenceSouscripteur;
        $souscripteurUser->telephone = $cim->telephoneSouscripeur1;
        $souscripteurUser->quartier = $cim->adresse_affilie;
        $souscripteurUser->code_postal = null;
        $souscripteurUser->adresse = null;
        $souscripteurUser->slack = null;
        $souscripteurUser->isMedicasure = 1;
        $souscripteurUser->isNotice = 1;
        $souscripteurUser->password = null;
        $souscripteurUser->decede = "non";
        $userResponse =  UserController::generatedUserFromMedicasure($souscripteurUser, 'Souscripteur');
        if ($userResponse->status() == 419)
            return $userResponse;

        $userSouscripteur = $userResponse->getOriginalContent()['user'];
        $passwordSouscripteur = $userResponse->getOriginalContent()['password'];
        $userSouscripteur->assignRole('Souscripteur');

        //Creation du compte souscripteurs
        $age = 0;

        if (!is_null($cim->dateNaissanceSouscripteur)) {
            $age = evaluateYearOfOld($cim->bornAroundSouscripteur);
        }

        $souscripteur = Souscripteur::create((array)$souscripteurUser + ['user_id' => $userSouscripteur->id, 'age' => $age]);
        Log::info("souscripteur créé");
        Log::info(json_encode($souscripteur));
        //defineAsAuthor("Souscripteur",$souscripteur->user_id,'create');

        //envoi des informations du compte utilisateurs par mail
        try {
            UserController::sendUserInformationViaMail($userSouscripteur, $passwordSouscripteur);
            //return response()->json(['souscripteur'=>$souscripteur]);
        } catch (\Swift_TransportException $transportException) {
            $message = "L'operation à reussi mais le mail n'a pas ete envoye. Verifier votre connexion internet ou contacter l'administrateur";
            //return response()->json(['souscripteur'=>$souscripteur, "message"=>$message]);
        }

        //Creation de l'utilisateur dans la table user et génération du mot de passe
        $patientUser = new User();
        $patientUser->nom = $cim->nomAffilie;
        $patientUser->prenom = $cim->prenomAffilie;
        $patientUser->email = $cim->emailSouscripteur1;
        $patientUser->nationalite = null;
        $patientUser->ville = $cim->villeResidenceAffilie;
        $patientUser->telephone = $cim->telephoneAffilie1;
        $patientUser->quartier = null;
        $patientUser->pays = null;
        $patientUser->code_postal = null;
        $patientUser->adresse = null;
        $patientUser->slack = null;
        $patientUser->isMedicasure = 1;
        $patientUser->isNotice = 1;
        $patientUser->password = null;
        $patientUser->decede = "non";
        $patientUser->souscripteur_id = $souscripteur->user_id;
        $patientResponse =  UserController::generatedUserFromMedicasure($patientUser, "Patient");

        if ($patientResponse->getOriginalContent()['user'] == null) {
            $this->revealError('nom', $patientResponse->getOriginalContent()['error']);
        }

        $patientUser = $patientResponse->getOriginalContent()['user'];
        $patientPassword = $patientResponse->getOriginalContent()['password'];
        $patientCode = $patientResponse->getOriginalContent()['code'];
        //Attribution du rôle patient
        $patientUser->assignRole('Patient');

        //Creation du compte patient

        $age = evaluateYearOfOld($cim->dateNaissanceAffilie);

        $patient = Patient::create((array)$patientUser + ['user_id' => $patientUser->id, 'age' => '44', 'date_de_naissance' => '2020-08-10']);

        //Définition de la question secrete et de la reponse secrete
        //ReponseSecrete::create($cim->only(['question_id','reponse'])+['user_id' => $patientUser->id]);

        //Generation du dossier client
        $dossier = DossierMedicalController::genererDossier($patient->user_id);
        Suivi::create([
            'dossier_medical_id' => $patient->dossier->id,
            'motifs' => 'Prise en charge initiale en attente',
            'categorie_id' => '1'
        ]);
        //defineAsAuthor("Patient",$patient->user_id,'create',$patient->user_id);

        //Ajout du patient à l'etablissement selectionné
        $etablissements = [4];
        Auth::loginUsingId(1);
        foreach ($etablissements as $etablissementId) {
            //Je verifie si ce patient n'est pas encore dans cette etablissement
            $nbre = EtablissementExercicePatient::where('etablissement_id', '=', $etablissementId)->where('patient_id', '=', $patient->user_id)->count();
            if ($nbre == 0) {
                $etablissement = EtablissementExercice::find($etablissementId);

                $etablissement->patients()->attach($patient->user_id);

                //defineAsAuthor("Patient",$patient->user_id,'add to etablissement id'.$etablissement->id,$patient->user_id);
            }
        }
        //Envoi des informations patient par mail
        $patient = Patient::with(['dossier', 'affiliations'])->restrictUser()->whereSlug($patient->slug)->first();
        $identifiant = $patient->dossier->numero_dossier;
        try {
            //Envoi de sms
            $user = $patient->user;
            //            $nom = (is_null($user->prenom) ? "" : ucfirst($user->prenom) ." ") . "". strtoupper( $user->nom);
            $nom = substr(strtoupper($user->nom), 0, 9);
            $this->sendSMS($user->telephone, trans('sms.accountCreated', ['nom' => $nom, 'password' => $patientCode, 'identifiant' => $identifiant], 'fr'));
            //!Envoi de sms

            UserController::sendUserPatientInformationViaMail($patientUser, $patientPassword);

            $patient = Patient::with('user', 'dossier')->where('user_id', '=', $patient->user_id)->first();
            $souscripteur = Souscripteur::with('user')->where('user_id', '=', $patient->souscripteur_id)->first();

            if (!is_null($souscripteur)) {

                $user = $souscripteur->user;
                $this->sendSmsToUser($user, null, $identifiant);

                $mail = new PatientAffiliated($souscripteur, $patient);
                $when = now()->addMinutes(1);
                Mail::to($souscripteur->user->email)->later($when, $mail);
            }


            return response()->json(['patient' => $patient, "password" => $patientPassword]);
        } catch (\Swift_TransportException $transportException) {
            $message = "L'operation à reussi mais le mail n'a pas ete envoye. Verifier votre connexion internet ou contacter l'administrateur";
            return response()->json(['patient' => $patient, "message" => $message]);
        }
        Log::info('patient create from medicasure');

        //$this->store($cim);

    }
    /**
     * Store a newly created resource in storage.
     * * @OA\Post(
     *      path="/patient",
     *      operationId="storeUser to medsurlink",
     *      tags={"Patient"},
     *      summary="Store patient",
     *      description="Returns user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     * @param patientStoreRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(patientStoreRequest $request)
    {

        //Creation de l'utilisateur dans la table user et génération du mot de passe
        $userResponse =  UserController::generatedUser($request, "Patient");

        if ($userResponse->getOriginalContent()['user'] == null) {
            $this->revealError('nom', $userResponse->getOriginalContent()['error']);
        }

        $user = $userResponse->getOriginalContent()['user'];
        $password = $userResponse->getOriginalContent()['password'];
        $code = $userResponse->getOriginalContent()['code'];
        //Attribution du rôle patient
        $user->assignRole('Patient');

        //Creation du compte patient

        $age = evaluateYearOfOld($request->date_de_naissance);

        $patient = Patient::create($request->except(['code_postal', 'quartier']) + ['user_id' => $user->id, 'age' => $age]);

        //Définition de la question secrete et de la reponse secrete
        ReponseSecrete::create($request->only(['question_id', 'reponse']) + ['user_id' => $user->id]);

        //Generation du dossier client
        $dossier = DossierMedicalController::genererDossier($patient->user_id);
        Suivi::create([
            'dossier_medical_id' => $patient->dossier->id,
            'motifs' => 'Prise en charge initiale en attente',
            'categorie_id' => '1'
        ]);
        defineAsAuthor("Patient", $patient->user_id, 'create', $patient->user_id);

        //Ajout du patient à l'etablissement selectionné
        $etablissements = $request->get('etablissement_id');
        foreach ($etablissements as $etablissementId) {
            //Je verifie si ce patient n'est pas encore dans cette etablissement
            $nbre = EtablissementExercicePatient::where('etablissement_id', '=', $etablissementId)->where('patient_id', '=', $patient->user_id)->count();
            if ($nbre == 0) {
                $etablissement = EtablissementExercice::find($etablissementId);

                $etablissement->patients()->attach($patient->user_id);

                defineAsAuthor("Patient", $patient->user_id, 'add to etablissement id' . $etablissement->id, $patient->user_id);
            }
        }


        //Envoi des informations patient par mail
        $patient = Patient::with(['dossier', 'affiliations'])->restrictUser()->whereSlug($patient->slug)->first();
        $identifiant = $patient->dossier->numero_dossier;
        try {
            //Envoi de sms
            $user = $patient->user;
            //            $nom = (is_null($user->prenom) ? "" : ucfirst($user->prenom) ." ") . "". strtoupper( $user->nom);
            $nom = substr(strtoupper($user->nom), 0, 9);
            $this->sendSMS($user->telephone, trans('sms.accountCreated', ['nom' => $nom, 'password' => $code, 'identifiant' => $identifiant], 'fr'));
            //!Envoi de sms

            UserController::sendUserPatientInformationViaMail($user, $password);

            $patient = Patient::with('user', 'dossier')->where('user_id', '=', $patient->user_id)->first();
            $souscripteur = Souscripteur::with('user')->where('user_id', '=', $patient->souscripteur_id)->first();

            if (!is_null($souscripteur)) {

                $user = $souscripteur->user;
                $this->sendSmsToUser($user, null, $identifiant);

                $mail = new PatientAffiliated($souscripteur, $patient);
                $when = now()->addMinutes(1);
                Mail::to($souscripteur->user->email)->later($when, $mail);
            }


            return response()->json(['patient' => $patient, "password" => $password]);
        } catch (\Swift_TransportException $transportException) {
            $message = "L'operation à reussi mais le mail n'a pas ete envoye. Verifier votre connexion internet ou contacter l'administrateur";
            return response()->json(['patient' => $patient, "message" => $message]);
        }
    }

    /**
     * Display the specified resource.
     ** Store a newly created resource in storage.
     * * @OA\Get(
     *      path="patient/{patient}",
     *      operationId="showUser",
     *      tags={"Patient"},
     *      summary="Show user",
     *      description="Returns user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $this->validatedSlug($slug, $this->table);

        /*$patient = Patient::with([
            'souscripteur.user',
            'user.questionSecrete',
            'affiliations',
            'etablissements',
            'financeurs.financable.user',
            'dossier',
        ])->restrictUser()->whereSlug($slug)->first();*/
        $patient = Patient::with([
            'souscripteur.user',
            'user.questionSecrete',
            'affiliations',
            'etablissements',
            'financeurs.financable.user',
            'dossier',
        ])->whereSlug($slug)->first();
        return response()->json(['patient' => $patient]);
    }

    /**
     * Display the specified resource.
     *
     ** Store a newly created resource in storage.
     * * @OA\Get(
     *      path="patient/search/{value}",
     *      operationId="SearchUser",
     *      tags={"Patient"},
     *      summary="Search user",
     *      description="Returns user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     * @param  string  $value
     * @return \Illuminate\Http\Response
     */

    public function specialList($value)
    {
        $result=[];
        $value = strtolower($value);
        $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable', 'medecinReferent.medecinControles.user'])
                            ->restrictUser()
                            ->whereHas('user', function($q) use ($value) {
                                $q->where(DB::raw("lower(nom)"), 'like', '%' .$value.'%')
                                ->orwhere(DB::raw("lower(prenom)"), 'like', '%' .$value.'%')
                                ->orwhere(DB::raw("lower(email)"), 'like', '%' .$value.'%')
                                ->orwhere(DB::raw('CONCAT_WS(" ", nom, prenom)'), 'like',  '%'.$value.'%')
                                ->orwhere(DB::raw('CONCAT_WS(" ", prenom, nom)'), 'like',  '%'.$value.'%');})
                            ->orwhereHas('dossier', function($q) use ($value) {$q->where('numero_dossier', 'like', '%' .$value.'%');})
                            ->orwhere('age', 'like', '%' .$value.'%')->get();
        return $patients;
        // $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable'])->where('age', '=', intval($value))->orWhereHas('user', function($q) use ($value){ $q->Where('nom', 'like', '%'.strtolower($value).'%'); $q->orWhere('prenom', 'like', '%'.strtolower($value).'%'); $q->orWhere('email', 'like', '%'.strtolower($value).'%');})->orWhereHas('dossier', function($q) use ($value){ $q->Where('numero_dossier', '=', intval($value)); })->restrictUser()->latest()->get();
        // return $patients;
        // foreach($patients as $p){

        //     if($p->user!=null){

        //         if(strpos(strtolower($p->user->nom),strtolower($value))!==false ||
        //     strpos(strtolower($p->user->prenom),strtolower($value))!==false ||
        //     strpos(strtolower(strval($p->dossier->numero_dossier)),strtolower($value))!==false ||
        //             strpos(strtolower(strval($p->age)),strtolower($value))!==false ||
        //     strpos(strtolower($p->user->email),strtolower($value))!==false) {
        //         // return $p;
        //         array_push($result,$p);
        //         // return $result;
        //     }


        //     }
        //     else{
        //         if(
        //             strpos(strtolower(strval($p->dossier->numero_dossier)),strtolower($value))!==false ||
        //             strpos(strtolower(strval($p->age)),strtolower($value))!==false)
        //             array_push($result,$p);
        //     }

        // }
        // return $result;

    }

    /**
     * Display the specified resource.
     * @param  string  $value
     * @return \Illuminate\Http\Response
     */

    public function PatientsDoctor($value)
    {
        // return $value;
        $result = [];
        $patients = Patient::with(['souscripteur', 'dossier', 'etablissements', 'user', 'affiliations', 'financeurs.financable', 'medecinReferent.medecinControles.user'])->restrictUser()->latest()->get();

        // $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable'])->where('age', '=', intval($value))->orWhereHas('user', function($q) use ($value){ $q->Where('nom', 'like', '%'.strtolower($value).'%'); $q->orWhere('prenom', 'like', '%'.strtolower($value).'%'); $q->orWhere('email', 'like', '%'.strtolower($value).'%');})->orWhereHas('dossier', function($q) use ($value){ $q->Where('numero_dossier', '=', intval($value)); })->restrictUser()->latest()->get();
        // return $patients;
        foreach ($patients as $p) {
            // if($p->user_id==629

            if (isset($p->medecinReferent)) {
                // return "true";
                foreach ($p->medecinReferent as $d) {

                    if ($d->medecinControles != null && $d->medecinControles->user != null) {
                        if ($d->medecinControles->user->id == $value) {
                            array_push($result, $p);
                        }
                    }
                }
            }
            // }
            // return "true";


        }
        return $result;
    }

    public function CountPatientsDoctor($value)
    {
        // return $value;
        $result = [];
        $patients = Patient::with(['souscripteur', 'dossier', 'etablissements', 'user', 'affiliations', 'financeurs.financable', 'medecinReferent.medecinControles.user'])->restrictUser()->latest()->get();

        // $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable'])->where('age', '=', intval($value))->orWhereHas('user', function($q) use ($value){ $q->Where('nom', 'like', '%'.strtolower($value).'%'); $q->orWhere('prenom', 'like', '%'.strtolower($value).'%'); $q->orWhere('email', 'like', '%'.strtolower($value).'%');})->orWhereHas('dossier', function($q) use ($value){ $q->Where('numero_dossier', '=', intval($value)); })->restrictUser()->latest()->get();
        // return $patients;
        foreach ($patients as $p) {
            // if($p->user_id==629

            if (isset($p->medecinReferent)) {
                // return "true";
                foreach ($p->medecinReferent as $d) {

                    if ($d->medecinControles != null && $d->medecinControles->user != null) {
                        if ($d->medecinControles->user->id == $value) {
                            array_push($result, $p);
                        }
                    }
                }
            }
            // }
            // return "true";


        }
        return count($result);
    }

    public function getAffiliations($patient_id)
    {
        /**
         * retourne les lignes de temps des différentes affiliation
         */
        $dossier_id = Patient::find($patient_id)->dossier->id;
        $ligne_temps = LigneDeTemps::with(['affiliation.package:id,description_fr', "motif:id,description"])->where('dossier_medical_id', $dossier_id)->latest()->get(['id', 'date_consultation', 'motif_consultation_id', 'affiliation_id', 'dossier_medical_id']);
        return response()->json(['ligne_temps' => $ligne_temps]);
    }

    public function getAffiliationLigneDeTemps($affiliation_id)
    {
        $ligne_temps = LigneDeTemps::where('affiliation_id', $affiliation_id)->with('motif:id,description')->latest()->get();
        return response()->json(['ligne_temps' => $ligne_temps]);
    }


    /**
     * @param  string  $value
     * @return \Illuminate\Http\Response
     */

    public function FirstPatientsDoctor($value, $limit)
    {
        // return $value;
        $result = [];
        $patients = Patient::with(['souscripteur', 'dossier', 'etablissements', 'user', 'affiliations', 'financeurs.financable', 'medecinReferent.medecinControles.user'])->restrictUser()->latest()->get();

        // $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable'])->where('age', '=', intval($value))->orWhereHas('user', function($q) use ($value){ $q->Where('nom', 'like', '%'.strtolower($value).'%'); $q->orWhere('prenom', 'like', '%'.strtolower($value).'%'); $q->orWhere('email', 'like', '%'.strtolower($value).'%');})->orWhereHas('dossier', function($q) use ($value){ $q->Where('numero_dossier', '=', intval($value)); })->restrictUser()->latest()->get();
        // return $patients;
        foreach ($patients as $p) {
            // if($p->user_id==629

            if (isset($p->medecinReferent)) {
                // return "true";
                foreach ($p->medecinReferent as $d) {

                    if ($d->medecinControles != null && $d->medecinControles->user != null) {
                        if ($d->medecinControles->user->id == $value) {
                            array_push($result, $p);
                        }
                    }
                }
            }
            // }
            // return "true";


        }
        return array_slice($result, 0, $limit);
    }

    public function NextPatientsDoctor($value, $limit, $page)
    {
        // return $value;
        $result = [];
        $patients = Patient::with(['souscripteur', 'dossier', 'etablissements', 'user', 'affiliations', 'financeurs.financable', 'medecinReferent.medecinControles.user'])->restrictUser()->latest()->get();

        // $patients = Patient::with(['souscripteur','dossier', 'etablissements', 'user','affiliations','financeurs.financable'])->where('age', '=', intval($value))->orWhereHas('user', function($q) use ($value){ $q->Where('nom', 'like', '%'.strtolower($value).'%'); $q->orWhere('prenom', 'like', '%'.strtolower($value).'%'); $q->orWhere('email', 'like', '%'.strtolower($value).'%');})->orWhereHas('dossier', function($q) use ($value){ $q->Where('numero_dossier', '=', intval($value)); })->restrictUser()->latest()->get();
        // return $patients;
        foreach ($patients as $p) {
            // if($p->user_id==629

            if (isset($p->medecinReferent)) {
                // return "true";
                foreach ($p->medecinReferent as $d) {

                    if ($d->medecinControles != null && $d->medecinControles->user != null) {
                        if ($d->medecinControles->user->id == $value) {
                            array_push($result, $p);
                        }
                    }
                }
            }
            // }
            // return "true";


        }
        return array_slice($result, ($page - 1) * $limit, $limit);
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
     * @OA\Put(
     *      path="patient/{patient}",
     *      operationId="Update user",
     *      tags={"Patient"},
     *      summary="Update user",
     *      description="Returns user update",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     * @param PatientUpdateRequest $request
     * @param $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PatientUpdateRequest $request, $slug)
    {
        $this->validatedSlug($slug, $this->table);

        $patient = Patient::with('user')->whereSlug($slug)->first();

        $response = UserController::updatePersonalInformation($request->except('patient', 'souscripteur_id', 'sexe', 'question_id', 'reponse'), $patient->user->slug);

        if ($response->getOriginalContent()['user'] == null) {
            $this->revealError('nom', $response->getOriginalContent()['error']);
        }

        $age = evaluateYearOfOld($request->date_de_naissance);

        Patient::whereSlug($slug)->update($request->only([
            "user_id",
            "souscripteur_id",
            "sexe",
            "date_de_naissance",
            "age",
            "nom_contact",
            "tel_contact",
            "lien_contact",
        ]) + ['age' => $age]);

        $patient = Patient::with(['souscripteur', 'user', 'affiliations'])->restrictUser()->whereSlug($slug)->first();

        //Mise à jour de la question et la reponse secrete
        if (is_null($patient->user->questionSecrete) || $patient->user->questionSecrete->isEmpty) {
            ReponseSecrete::create($request->only(['question_id', 'reponse']) + ['user_id' => $patient->user->id]);
        } else {
            ReponseSecrete::where('user_id', '=', $patient->user_id)->update($request->only(['question_id', 'reponse']));
        }

        try {

            if (!is_null($patient->user->email)) {
                $mail = new updateSetting($patient->user);
                $when = now()->addMinutes(1);
                Mail::to($patient->user->email)->later($when, $mail);
            }
        } catch (\Swift_TransportException $transportException) {
            $message = "L'operation à reussi mais le mail n'a pas ete envoye. Verifier votre connexion internet ou contacter l'administrateur";
            return response()->json(['patient' => $patient, "message" => $message]);
        }
        return response()->json(['patient' => $patient]);
    }

    /**
     * Remove the specified resource from storage.
     * @OA\DELETE(
     *      path="patient/{patient}",
     *      operationId="Delete user",
     *      tags={"Patient"},
     *      summary="Delete user",
     *      description="Returns user delete",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     * @param $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($slug)
    {
        $this->validatedSlug($slug, $this->table);

        try {
            $patient = Patient::with(['souscripteur', 'user', 'affiliations'])->restrictUser()->whereSlug($slug)->first();
            $dossier = $patient->dossier;
            if (!is_null($dossier)) {
                $dossier->delete();
                defineAsAuthor("DossierMedical", $dossier->id, 'delete', $patient->user_id);
            }
            $patient->delete();
            defineAsAuthor("Patient", $patient->user_id, 'delete', $patient->user_id);
            return response()->json(['patient' => $patient]);
        } catch (DeleteRestrictionException $deleteRestrictionException) {
            $this->revealError('deletingError', $deleteRestrictionException->getMessage());
        }
    }

    /**
     * Fonction permettant de générer un nouveau mot de passe pour un numero de dossier précis
     * @param Request $request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'numero_dossier' => "required|string|exists:dossier_medicals,numero_dossier",
            'date_de_naissance' => "required|date",
            'telephone' => 'required|string|min:9',
            'question_id' => 'integer|nullable',
            'reponse' => 'nullable|string|min:3'
        ]);

        $dossier = DossierMedical::where('numero_dossier', $request->get('numero_dossier'))->first();
        $user = $dossier->patient->user;
        $questionSecrete = $user->questionSecrete;
        //Verification du numero de telephone
        if ($user->telephone != $request->get('telephone')) {
            $this->revealError('telephone', 'Phone invalid');
        }
        //Verification de la question de securite
        if ($questionSecrete->question_id != $request->get('question_id')) {
            $this->revealError('question_id', 'Secret question or answer invalid');
        }
        //Verification de la reponse de securite
        if (strtoupper($questionSecrete->reponse) != strtoupper($request->get('reponse'))) {
            $this->revealError('question_id', 'Secret question or answer invalid');
        }

        $password = str_random(10);
        $code = "";
        $date_naissance = Carbon::parse($request->get('date_de_naissance'))->year;
        $code = substr($password, 0, 5);
        $password = $date_naissance . $code;

        //        $nom = (is_null($user->prenom) ? "" : ucfirst($user->prenom) ." ") . "". strtoupper( $user->nom);
        $nom = substr(strtoupper($user->nom), 0, 20);
        $user->password = bcrypt($password);
        $user->save();
        if ($user->decede == 'non') {
            sendSMS($request->get('telephone'), trans('sms.accountSecurityUpdated', ['nom' => $nom, 'password' => $code], 'fr'));
        }
        return response()->json(['message' => 'Sms envoyé avec succès']);
    }

    public function decede(Request $request, $slug)
    {
        $this->validatedSlug($slug, $this->table);
        $patient = Patient::with(['souscripteur', 'user', 'affiliations'])->restrictUser()->whereSlug($slug)->first();
        $user = User::whereId($patient->user_id)->first();

        $user->decede = $request->get('decede');
        $user->save();
        $patient = Patient::with(['souscripteur', 'user', 'affiliations'])->restrictUser()->whereSlug($slug)->first();
        return response()->json(['patient' => $patient]);
    }

    public function assignation_souscripteur(Request $request)
    {
        $patient = Patient::find($request->patient_id);
        $patient->souscripteur_id = $request->souscripteur_id;
        $patient->save();
        return response()->json(['patient' => 'success']);
    }

    public function getPatientWithMedecin()
    {
        $patients = Patient::with(['souscripteur', 'dossier', 'user', 'affiliations', 'medecinReferent.medecinControles.user'])->restrictUser()->whereHas('user', function ($q) {
            $q->where('isMedicasure', '=', 1)->where('decede', '=', 'non');
        })->latest()->get();
        return response()->json(['patients' => $patients]);
    }

    public function getFirstPatientWithMedecin($limit)
    {
        $patients = Patient::with(['souscripteur', 'dossier', 'user', 'affiliations', 'medecinReferent.medecinControles.user'])->restrictUser()->whereHas('user', function ($q) {
            $q->where('isMedicasure', '=', 1)->where('decede', '=', 'non');
        })->take($limit)->latest()->get();
        return response()->json(['patients' => $patients]);
    }

    public function getNextPatientWithMedecin($limit, $page)
    {
        $patients = Patient::with(['souscripteur', 'dossier', 'user', 'affiliations', 'medecinReferent.medecinControles.user'])->restrictUser()->whereHas('user', function ($q) {
            $q->where('isMedicasure', '=', 1)->where('decede', '=', 'non');
        })->limit($limit)->offset(($page - 1) * $limit)->latest()->get();
        return response()->json(['patients' => $patients]);
    }

    // public function get10PatientWithMedecin()
    // {
    //     $patients = Patient::with(['souscripteur','dossier','user','affiliations','medecinReferent.medecinControles.user'])->restrictUser()->take(10)->latest()->get();
    //     return response()->json(['patients'=>$patients]);
    // }

    // public function get15PatientWithMedecin()
    // {
    //     $patients = Patient::with(['souscripteur','dossier','user','affiliations','medecinReferent.medecinControles.user'])->restrictUser()->take(15)->latest()->get();
    //     return response()->json(['patients'=>$patients]);
    // }

    // public function get100PatientWithMedecin()
    // {
    //     $patients = Patient::with(['souscripteur','dossier','user','affiliations','medecinReferent.medecinControles.user'])->restrictUser()->take(100)->latest()->get();
    //     return response()->json(['patients'=>$patients]);
    // }

    public function getCountPatientWithMedecin()
    {
        $patients = Patient::with(['souscripteur', 'dossier', 'user', 'affiliations', 'medecinReferent.medecinControles.user'])->restrictUser()->whereHas('user', function ($q) {
            $q->where('isMedicasure', '=', 1)->where('decede', '=', 'non');
        })->count();
        return response()->json(['count' => $patients]);
    }
    public function searchPatients(Request $request)
    {
        $data = User::with(['patient', 'patient.dossier'])->where('nom', 'LIKE', '%' . $request->keyword . '%')->latest()->get();
        return response()->json($data);
    }
}
