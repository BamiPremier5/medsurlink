<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\PersonnalErrors;
use App\Http\Requests\RendezVousRequest;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RendezVousController extends Controller
{
    use PersonnalErrors;
    protected $table = 'rendez_vous';
    /**
     * Display a listing of the resource.
     * Retourne les rdv dans l'intervale [$nbre de mois avant $dateDebut, $nbre de mois apres $dateDebut]
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->rendez_vous_manques == "rendez_vous_manques"){
            return $this->rendez_vous_manques();
        }
        elseif($request->rendez_vous_manques == "rendez_vous_effectues"){
            return $this->rendez_vous_effectues();
        }
        else{
            $dateDebut = $request->get('date_debut');
            $nbre = $request->get('nbre',1);
            $userId = Auth::id();

            try {
                $dateDebut = Carbon::parse($dateDebut);
            }catch (\Exception $exception){
                $dateDebut = Carbon::now();
            }
            //On récupère les rendez entre ces deux dates
            $dateAvant = date('Y-m-d', strtotime($dateDebut. ' - '.$nbre.' months'));
            $dateApres = date('Y-m-d', strtotime($dateDebut. ' + '.$nbre.' months'));

            $rdvs = RendezVous::has('patient')->with(['patient','praticien','sourceable','initiateur'])
                ->where(function ($query) {
                $query->where('statut', "Programmé")->orWhere('statut', "Reprogrammé");
            })->where('praticien_id','=',$userId)->orWhere('patient_id','=',$userId)
                ->orWhere('initiateur','=',$userId)->get();

            $rdvsAvant = $rdvs->where('date','>=',$dateAvant)->all();

            $rdvsApres = $rdvs->where('date','>=',$dateApres)->all();

            //Ici on récupère les rendez vous des autres praticiens et médécin
            $user = Auth::user();
            $roleName = $user->getRoleNames()->first();
            if ($roleName == 'Praticien' || $roleName == 'Medecin controle' || $roleName == 'Admin' || $roleName == 'Gestionnaire' || $roleName == 'Assistante' || $roleName == 'Pharmacien'){

                if (strpos($user->email,'@medicasure.com')){
                    $rdvDesAutres = RendezVous::with(['patient','praticien','sourceable','initiateur'])
                    ->where(function ($query) {
                        $query->where('statut', "Programmé")->orWhere('statut', "Reprogrammé");
                    })->where('praticien_id','<>',$userId)->get();
                    $rdvsApres = $rdvsApres + $rdvDesAutres->where('date','>=',$dateApres)->all();
                    $rdvsAvant = $rdvsAvant + $rdvDesAutres->where('date','>',$dateAvant)->all();
                }
            }
            $rdvs = $rdvsAvant+$rdvsApres;
            foreach ($rdvs as $rdv){
                if($rdv->patient){
                    $rdv->updateRendezVous();
                }
            }
            return response()->json(['rdvs' => $rdvs]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rendez_vous_manques(){
        /**
         * Recupération des rendez-vous manqués des 90 derniers jours
         */
        $userId = Auth::id();
        $date_debut = Carbon::now()->subDays(90)->format('Y-m-d');
        $date_fin = Carbon::now()->format('Y-m-d');

        $rdvs = RendezVous::with(['patient','praticien','sourceable','initiateur'])->has('patient')
        ->where(function ($query) use($userId) {
            $query->where('praticien_id', $userId)->orWhere('patient_id', $userId)->orWhere('initiateur', $userId);
        })->Jours306090($date_debut, $date_fin)->get();


        //Ici on récupère les rendez vous des autres praticiens et médécin
        $user = Auth::user();
        $roleName = $user->getRoleNames()->first();
        if ($roleName == 'Praticien' || $roleName == 'Medecin controle' || $roleName == 'Admin' || $roleName == 'Gestionnaire' || $roleName == 'Assistante' || $roleName == 'Pharmacien'){

            if (strpos($user->email,'@medicasure.com')){
                $rdvDesAutres = RendezVous::with(['patient','praticien','sourceable','initiateur'])->has('patient')
                ->Jours306090($date_debut, $date_fin)->get();
                $rdvs = $rdvs->merge($rdvDesAutres)->sortByDesc('date');
                $rdvs = $rdvs->values()->all();
            }
        }
        foreach ($rdvs as $rdv){
            $rdv->updateRendezVous();
        }
        return response()->json(['rdvs' => $rdvs]);
    }

    /**
     * Recuparation des rendez-vous effectué
     *
     * @return \Illuminate\Http\Response
     */
    public function rendez_vous_effectues(){
        /**
         * Recupération des rendez-vous manqués des 90 derniers jours
         */
        $userId = Auth::id();
        $date_debut = Carbon::now()->subDays(90)->format('Y-m-d');
        $date_fin = Carbon::now()->format('Y-m-d');

        $rdvs = RendezVous::with(['patient','praticien','sourceable','initiateur'])->has('patient')
        ->where(function ($query) use($userId) {
            $query->where('praticien_id', $userId)->orWhere('patient_id', $userId)->orWhere('initiateur', $userId);
        })->Effectues306090($date_debut, $date_fin)->get();


        //Ici on récupère les rendez vous des autres praticiens et médécin
        $user = Auth::user();
        $roleName = $user->getRoleNames()->first();
        if ($roleName == 'Praticien' || $roleName == 'Medecin controle' || $roleName == 'Admin' || $roleName == 'Gestionnaire' || $roleName == 'Assistante' || $roleName == 'Pharmacien'){

            if (strpos($user->email,'@medicasure.com')){
                $rdvDesAutres = RendezVous::with(['patient','praticien','sourceable','initiateur'])->has('patient')
                ->Effectues306090($date_debut, $date_fin)->get();
                $rdvs = $rdvs->merge($rdvDesAutres)->sortByDesc('date');
                $rdvs = $rdvs->values()->all();
            }
        }
        foreach ($rdvs as $rdv){
            $rdv->updateRendezVous();
        }
        return response()->json(['rdvs' => $rdvs]);
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
    public function store(RendezVousRequest $request)
    {
        //Auth::loginUsingId(77);
        //Récupération du nom du medecin ou bien de l'identifiant du praticien
        $praticien = $request->get('praticien_id');

        $praticienId = (integer) $praticien;

        if ($praticienId !== 0){
            $validator = Validator::make(['praticien_id'=>$praticienId],['praticien_id'=>'required|integer|exists:users,id']);

            if($validator->fails()){
                return $this->revealError('praticien_id','le praticien spécifié n\'exite pas dans la bd');
            }else{
                $rdv = RendezVous::create($request->except('praticien_id') + ['praticien_id'=>$praticienId,'initiateur'=>Auth::id()]);
            }
        }else{

            if ($praticien != ""){

                $rdv = RendezVous::create($request->except('praticien_id') + ['nom_medecin'=>$praticien,'initiateur'=>Auth::id()]);
            }
        }

        defineAsAuthor("RendezVous", $rdv->id, 'create');
        $rdv->updateRendezVous();

        return response()->json(['rdv'=>$rdv]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $this->validatedSlug($slug,$this->table);

        $rdv = RendezVous::with(['patient','praticien','sourceable','initiateur','etablissement'])
            ->whereSlug($slug)
            ->first();

        return response()->json(['rdv'=>$rdv]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $this->validatedSlug($slug,$this->table);

        $rdv = RendezVous::findBySlugOrFail($slug);

        return response()->json(['rdv'=>$rdv]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(RendezVousRequest $request, $slug)
    {
        $rdv = RendezVous::with(['patient','praticien','sourceable','initiateur'])->WhereSlug($slug)->first();

        if($request->statut == "Effectué"){
            $rdv->statut = $request->statut;
            $rdv->save();
        }elseif($request->statut == "Reprogrammé"){
            /**
             * Annulons le rendez-vous pour créer un autre comme enfant de ce dernier
             */
            $rdv->statut = $request->statut == "Reprogrammé" ? "Annulé" : $request->statut;
            $rdv->save();
            $rdv = RendezVous::create([
                "sourceable_id" => $rdv->sourceable_id,
                "sourceable_type" => $rdv->sourceable_type,
                "patient_id" => $rdv->patient_id,
                "praticien_id" => $rdv->praticien_id,
                "initiateur" => $rdv->initiateur,
                "motifs" => $rdv->motifs,
                "date" => $request->date,
                "statut" => $request->statut,
                "nom_medecin" => $rdv->nom_medecin,
                "ligne_temps_id" => $rdv->ligne_temps_id,
                "consultation_id" => $rdv->consultation_id,
                'etablissement_id' => $rdv->etablissement_id,
                'parent_id' => $rdv->id
            ]);
        }
        else{
            $this->validatedSlug($slug,$this->table);

            //Récupération du nom du medecin ou bien de l'identifiant du praticien
            $praticien = $request->get('praticien_id');

            $praticienId = (integer) $praticien;

            if ($praticienId !== 0){
                RendezVous::whereSlug($slug)->update($request->except('praticien_id') + ['praticien_id'=>$praticienId,'initiateur'=>Auth::id()]);
            }else{
                if ($praticien != ""){
                    RendezVous::whereSlug($slug)->update($request->except('praticien_id') + ['praticien_id'=>null,'nom_medecin'=>$praticien,'initiateur'=>Auth::id()]);
                }
            }
        }

        defineAsAuthor("RendezVous", $rdv->id, $request->statut == "Reprogrammé" ? 'create' : 'update');
        $rdv->updateRendezVous();
        return response()->json(['rdv'=>$rdv]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $this->validatedSlug($slug,$this->table);

        $rdv = RendezVous::findBySlugOrFail($slug);
        $rdv->delete();

        defineAsAuthor("RendezVous", $rdv->id, 'delete');

        return  response()->json(['rdv'=>$rdv]);
    }
}
