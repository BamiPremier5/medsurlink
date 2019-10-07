<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\PersonnalErrors;
use App\Http\Requests\GestionnaireStoreRequest;
use App\Http\Requests\GestionnaireUpdateRequest;
use App\Models\Gestionnaire;
use Illuminate\Support\Facades\Auth;

class GestionnaireController extends Controller
{
    use PersonnalErrors;
    protected $table = "gestionnaires";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gestionnaires = Gestionnaire::with('user')->get();

        foreach ($gestionnaires as $gestionnaire){
           $gestionnaire->updateGestionnaire();
        }

        return response()->json(['gestionnaires'=>$gestionnaires]);
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
    public function store(GestionnaireStoreRequest $request)
    {

        //Création des informations utilisateurs
        $userResponse =  UserController::generatedUser($request);

        $user = $userResponse->getOriginalContent()['user'];
        $password = $userResponse->getOriginalContent()['password'];
        $user->assignRole('Gestionnaire');

        $gestionnaire = Gestionnaire::create($request->validated() + ['user_id' => $user->id]);
        defineAsAuthor("Gestionnaire",$gestionnaire->user_id,'create');

        //envoi des informations du compte utilisateurs par mail
        UserController::sendUserInformationViaMail($user,$password);

        return response()->json(['gestionnaire'=>$gestionnaire]);

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
        $this->validatedSlug($slug,$this->table);

        $gestionnaire = Gestionnaire::with('user')->whereSlug($slug)->first();

        $gestionnaire->updateGestionnaire();

        return response()->json(['gestionnaire'=>$gestionnaire]);

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
     * @param GestionnaireUpdateRequest $request
     * @param $slug
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PersonnnalException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(GestionnaireUpdateRequest $request, $slug)
    {
        $this->validatedSlug($slug,$this->table);

        $gestionnaire = Gestionnaire::with('user')->whereSlug($slug)->first();

        UserController::updatePersonalInformation($request->except('civilite','manager'),$gestionnaire->user->slug);

        $isAuthor = checkIfIsAuthorOrIsAuthorized("Gestionnaire",$gestionnaire->user_id,"create");
        if(!$isAuthor->getOriginalContent() && $gestionnaire->user_id != Auth::id())
            {
                $this->revealAccesRefuse();
            }

        Gestionnaire::whereSlug($slug)->update($request->validated());

        $gestionnaire = Gestionnaire::with('user')->whereSlug($slug)->first();

        return response()->json(['gestionnaire'=>$gestionnaire]);

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

        $gestionnaire = Gestionnaire::whereSlug($slug)->first();
        $gestionnaire->delete();

        return response()->json(['gestionnaire'=>$gestionnaire]);

    }

}
