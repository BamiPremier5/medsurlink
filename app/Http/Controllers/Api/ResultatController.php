<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ResultatRequest;
use App\Models\Resultat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        if($request->hasFile('file')){
            if ($request->file('file')->isValid()) {
                $resultat = Resultat::create($request->validated());

                $path = $request->file->store('Dossier Medicale/'.$resultat->dossier->numero_dossier.'/Consultation/'.$request->consultation_medecine_generale_id);
                $file = $path;
                $resultat->file = $file;
                $resultat->save();
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
    public function show($id)
    {
        $validation = validatedId($id,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::with(['dossier','consultation'])->find($id);
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
    public function update(ResultatRequest $request, $id)
    {
        $validation = validatedId($id,$this->table);
        if(!is_null($validation))
            return $validation;

        Resultat::whereId($id)->update($request->validated());
        $resultat = Resultat::with(['dossier','consultation'])->find($id);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validation = validatedId($id,$this->table);
        if(!is_null($validation))
            return $validation;

        $resultat = Resultat::with(['dossier','consultation'])->find($id);
        Resultat::destroy($id);
        return response()->json(['resultat'=>$resultat]);
    }
}
