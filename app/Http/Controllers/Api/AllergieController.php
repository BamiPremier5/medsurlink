<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AllergieRequest;
use App\Models\Allergie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllergieController extends Controller
{
    protected  $table = "allergies";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allergies = Allergie::all();
        return response()->json(['allergies'=>$allergies]);
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
    public function store(AllergieRequest $request)
    {
        if ($request->has('error'))
        {
            return  response()->json(['error'=>$request->all()['error']],419);
        }
        $allergie = Allergie::create($request->validated());
        defineAsAuthor("Allergie",$allergie->id,'create');
        return response()->json(['allergie'=>$allergie]);

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

        $allergie = Allergie::findBySlug($slug);
        return response()->json(['allergie'=>$allergie]);

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
    public function update(AllergieRequest $request, $slug)
    {
        if ($request->has('error'))
        {
            return  response()->json(['error'=>$request->all()['error']],419);
        }
        $validation = validatedSlug($slug,$this->table);
        if(!is_null($validation))
            return $validation;

        Allergie::whereSlug($slug)->update($request->validated());
        $allergie = Allergie::findBySlug($slug);
        return response()->json(['allergie'=>$allergie]);
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

        $allergie = Allergie::findBySlug($slug);
        $allergie->delete();
        return response()->json(['allergie'=>$allergie]);
    }
}
