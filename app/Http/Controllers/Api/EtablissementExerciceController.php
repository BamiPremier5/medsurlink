<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EtablissementExerciceRequest;
use App\Models\EtabissementExercice;
use Illuminate\Support\Facades\Validator;

/**
 * Class EtablissementExerciceController
 * @package App\Http\Controllers\Api
 */
class EtablissementExerciceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $etablissements =  EtabissementExercice::all();
        return response()->json(['etablissements'=>$etablissements]);
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
    public function store(EtablissementExerciceRequest $request)
    {
        $etablissement = EtabissementExercice::create($request->validated());
        return response()->json(['etablissement'=>$etablissement]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->validatedId($id);

        $etablissement = EtabissementExercice::find($id);
        return response()->json(['etablissement'=>$etablissement]);


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
    public function update(EtablissementExerciceRequest $request, $id)
    {
        $this->validatedId($id);

        EtabissementExercice::whereId($id)->update($request->validated());
        $etablissement = EtabissementExercice::find($id);
        return response()->json(['etablissement'=>$etablissement]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->validatedId($id);
        $etablissement = EtabissementExercice::find($id);
        EtabissementExercice::destroy($id);
        return response()->json(['etablissement'=>$etablissement]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function validatedId($id){
        $validation = Validator::make(compact('id'),['id'=>'exists:etabissement_exercices,id']);
        if ($validation->fails()){
            return response()->json(['id'=>$validation->errors()],422);
        }
    }
}
