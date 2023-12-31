<?php

namespace App\Http\Controllers\Api\v2\Teleconsultation;

use App\Http\Controllers\Controller;
use App\Models\DossierMedical;
use App\Models\Patient;
use App\Services\ExamenAnalyseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamenAnalyseController extends Controller
{

    private $examenAnalyseService;

    /**
     * 
     *
     * @param \App\Services\ExamenAnalyseService $examenAnalyseService
     */
    public function __construct(ExamenAnalyseService $examenAnalyseService)
    {
        $this->examenAnalyseService = $examenAnalyseService;
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        $patient_search = $request->search;
        $patients = searchPatient($patient_search);
        $request->request->add(['patients' => $patients]);

        return $this->successResponse($this->examenAnalyseService->fetchExamenAnalyses($request));
    }

    /**
     * @param $examenAnalyse
     *
     * @return mixed
     */
    public function show($examenAnalyse)
    {
        return $this->successResponse($this->examenAnalyseService->fetchExamenAnalyse($examenAnalyse));
    }

    /**
     * @param $patient_id
     *
     * @return mixed
     */
    public function getExamenAnalyses(Request $request, $patient_id)
    {
        $dossier = DossierMedical::whereSlug($patient_id)->latest()->first();
        if(!is_null($dossier)){
            $patient_id = $dossier->patient_id;
        }
        return $this->successResponse($this->examenAnalyseService->getExamenAnalyses($request, $patient_id));
    }


    public function getPatientBulletins($patient_id)
    {
        return $this->successResponse($this->examenAnalyseService->getPatientBulletins($patient_id));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->request->add(['creator' => \Auth::guard('api')->user()->id]);
        return $this->successResponse($this->examenAnalyseService->createExamenAnalyse($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $examenAnalyse
     *
     * @return mixed
     */
    public function update(Request $request, $examenAnalyse)
    {
        $request->request->add(['creator' => \Auth::guard('api')->user()->id]);
        return $this->successResponse($this->examenAnalyseService->updateExamenAnalyse($examenAnalyse, $request->all()));
    }

    /**
     * @param $examenAnalyse
     *
     * @return mixed
     */
    public function destroy($examenAnalyse)
    {
        return $this->successResponse($this->examenAnalyseService->deleteExamenAnalyse($examenAnalyse));
    }
}
