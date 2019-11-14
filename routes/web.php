<?php
use App\Models\ContratIntermediationMedicale;
use Barryvdh\DomPDF\Facade as PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**-- Headers --**/
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE, PATCH');
header('Access-Control-Allow-Headers:  Origin, Content-Type, X-Auth-Token, Authorization, X-Requested-With, x-xsrf-token');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/public/storage/DossierMedicale/{fileNumber}/Consultation/{consultation}/{image}', function ($fileNumber,$consultation,$image) {
    $path = public_path().'/storage/DossierMedicale/'.$fileNumber.'/Consultation/'.$consultation.'/'.$image;
    return response()->file($path);
});

Route::get('/contrat/{id}', function ($id) {

    $cim= ContratIntermediationMedicale::find($id);
    return view('contrat',compact('cim'));
});

Route::get('imprimer/contrat/{id}', function ($id) {
    $cim = ContratIntermediationMedicale::find($id);
    $data = ['cim'=>$cim];
    $pdf = PDF::loadView('contrat_version_imprimable',$data);
    return $pdf->download("Contrat d'intermediation medicale - ".strtoupper($cim->nomPatient)." ".ucfirst($cim->prenomPatient)." - ".ucfirst($cim->typeSouscription).".pdf");
});

Route::get('{all}', function () {
    return view('dashboard');
//})->where('all', '^(dashboard).*$');
})//->middleware('auth','isAdmin')
->where('all', '^admin|admin/|admin/.*,dashboard|dashboard/|dashboard/.*$');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
