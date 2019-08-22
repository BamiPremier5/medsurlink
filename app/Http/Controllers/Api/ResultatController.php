<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ResultatRequest;
use App\Models\Resultat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ResultatController extends Controller
{
    protected $table = "resultats";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resultats = Resultat::with(['dossier','consultation'])->get();
        return response()->json(['resultats'=>$resultats]);
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
    public function store(ResultatRequest $request)
    {
        if ($request->has('error'))
        {
            return  response()->json(['error'=>$request->all()['error']],419);
        }

        if($request->hasFile('file')){
            if ($request->file('file')->isValid()) {
                $resultat = Resultat::create($request->validated());

                $path = $request->file->store('Dossier Medicale/'.$resultat->dossier->numero_dossier.'/Consultation/'.$request->consultation_medecine_generale_id);
                $file = $path;
                $resultat->file = $file;
                $resultat->save();

                defineAsAuthor("Resultat",$resultat->id,'create');

            }
        }else{
            return response()->json(['file'=>"File required"],422);
        }

        return response()->json(['resultat'=>$resultat]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::with(['dossier','consultation'])->whereSlug($slug)->first();
        return response()->json(['resultat'=>$resultat]);
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
    public function update(ResultatRequest $request, $slug)
    {
        if ($request->has('error'))
        {
            return  response()->json(['error'=>$request->all()['error']],419);
        }

        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::findBySlug($slug);
        $isAuthor = checkIfIsAuthorOrIsAuthorized("Resultat",$resultat->id,"create");
        if($isAuthor->getOriginalContent() == false){
            return response()->json(['error'=>"Vous ne pouvez modifié un élement que vous n'avez crée"],401);
        }

        Resultat::whereSlug($slug)->update($request->validated());
        $resultat = Resultat::with(['dossier','consultation'])->whereSlug($slug)->first();

        if($request->hasFile('file')){
            if ($request->file('file')->isValid()) {
                $path = $request->file->store('Dossier Medicale/'.$resultat->dossier->numero_dossier.'/Consultation/'.$request->consultation_medecine_generale_id);
                $file = $path;
                $resultat->file = $file;
                $resultat->save();
            }
        }
        return response()->json(['resultat'=>$resultat]);
    }

    /**
     * Archieved the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archiver($slug)
    {
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::with(['dossier','consultation'])->whereSlug($slug)->first();
        if (is_null($resultat->passed_at)){
            return response()->json(['error'=>"Ce resultat n'a pas encoré été transmis"],401);
        }else{
            $resultat->archieved_at = Carbon::now();
            $resultat->save();
            return response()->json(['resultat'=>$resultat]);
        }
    }

    /**
     * Passed the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transmettre($slug)
    {
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::with(['dossier','consultation'])->whereSlug($slug)->first();
        $resultat->passed_at = Carbon::now();
        $resultat->save();

        return response()->json(['resultat'=>$resultat]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::findBySlug($slug);
        $isAuthor = checkIfIsAuthorOrIsAuthorized("Resultat",$resultat->id,"create");
        if($isAuthor->getOriginalContent() == false){
            return response()->json(['error'=>"Vous ne pouvez modifié un élement que vous n'avez crée"],401);
        }

        $resultat->delete();
        return response()->json(['resultat'=>$resultat]);
    }
}