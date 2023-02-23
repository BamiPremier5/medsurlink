<?php

namespace App\Http\Controllers\Api\v2\Teleconsultation;

use App\Http\Controllers\Controller;
use App\Services\PatientService;
use App\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{

    private $prescription;

    /**
     * PrescriptionController constructor.
     *
     * @param \App\Services\PrescriptionService $prescription
     */
    public function __construct(PrescriptionService $prescription)
    {
        $this->prescription = $prescription;
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->successResponse($this->prescription->fetchPrescriptions($request));
    }

    /**
     * @param $prescription
     *
     * @return mixed
     */
    public function show($prescription)
    {
        return $this->successResponse($this->prescription->fetchPrescription($prescription));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->successResponse($this->prescription->createPrescription($request->all()));
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $prescription
     *
     * @return mixed
     */
    public function update(Request $request, $prescription)
    {
        return $this->successResponse($this->prescription->updatePrescription($prescription, $request->all()));
    }

    /**
     * @param $prescription
     *
     * @return mixed
     */
    public function destroy($prescription)
    {
        return $this->successResponse($this->prescription->deletePrescription($prescription));
    }
}