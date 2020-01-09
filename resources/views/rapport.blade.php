<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900&display=swap' rel='stylesheet'>
    <link href="{{ asset('css/rapportMedical.css') }}" rel="stylesheet">
    <title>Medicalink</title>
</head>
<body>

@isset($dossier)
    <div class="justify-content-center">
        <h2>{{is_null($dossier->patient->user->prenom) ? "" :  is_null($dossier->patient->user->prenom) }} {{$dossier->patient->user->nom}}</h2>
        <h3>{{$dossier->patient->date_de_naissance}}</h3>
        <h3>Sexe : {{$dossier->patient->sexe}}</h3>
    </div>

    <div class="justify-content-center"> Allergies Information </div>
    @forelse($dossier->allergies as $allergie)
        <table>
            <thead>
            <td>Description</td>
            <td>Date debut</td>
            </thead>
            <tbody>
            <td>{{$allergie->description}}</td>
            <td>{{$allergie->date}}</td>
            </tbody>
        </table>
    @empty
        <p>Aucune allergie</p>
    @endforelse

@endisset

@isset($consultationObstetrique)
@endisset

@isset($consultationMedecine)
    <div style="color: black">
        @if(!is_null($consultationMedecine->etablissement))
            <div>
                @if(!is_null($consultationMedecine->etablissement->logo))
                    <div style="margin-left: 35%">
                        <img src="{{public_path('/storage/'.$consultationMedecine->etablissement->logo)}}" height="50px" width="150px"/>
                    </div>
                @endif
                <p style="text-align: center">{{$consultationMedecine->etablissement->name}}</p>
            </div>
        @endif

        <p style="text-align: center; text-transform: uppercase"><strong>Rapport de consultation</strong></p>
        <br>
        <p>Honorée Consoeur, Honorée Confrère,</p>
        <p>J'ai vue en date du <strong>{{\Carbon\Carbon::parse($consultationMedecine->date_consultation)->format('d/m/Y')}}</strong>, pour une consultation médecine générale, votre patient(e)
            <strong>{{$consultationMedecine->dossier->patient->user->nom}}</strong> né(e) le <strong>{{\Carbon\Carbon::parse($consultationMedecine->dossier->patient->date_de_naissance)->format('d/m/Y')}}</strong>
            pour:
            @forelse($consultationMedecine->motifs as $motif)
                <strong>{{$motif->description}},</strong>
            @empty
                <strong></strong>
            @endforelse
        </p>

        <h4>Motif(s) Consultation</h4>
        @forelse($consultationMedecine->motifs as $motif)
            <p>{{$motif->description}}</p>
        @empty
            <strong></strong>
        @endforelse

        <h4>Anamnèse</h4>
        <p>{{$consultationMedecine->anamese}}</p>

        <h4>Mode de vie</h4>
        <p class="ml-5">Profession : <strong>{{$consultationMedecine->profession}}</strong></p>
        <p class="ml-5">Situation familiale : <strong>{{$consultationMedecine->situation_familiale}}</strong></p>
        <p class="ml-5">Nombre d'enfants : <strong>{{$consultationMedecine->nbre_enfant}}</strong></p>
        <p class="ml-5">Tabac : <strong>{{$consultationMedecine->tabac}}</strong></p>
        <p class="ml-5">Alcool : <strong>{{$consultationMedecine->alcool}}</strong></p>
        <p class="ml-5">Autres : <strong>{{$consultationMedecine->autres}}</strong></p>

        <h4>Antédédents</h4>
        <table>
            <thead>
            <td>Type</td>
            <td>Description</td>
            <td>Date debut</td>
            </thead>
            <tbody>
            <tr></tr>
            @forelse($consultationMedecine->dossier->antecedents as $antecedent)
                <tr>
                    <td>{{$antecedent->type}}</td>
                    <td>{{$antecedent->description}}</td>
                    <td>{{\Carbon\Carbon::parse($antecedent->date)->format('d/m/Y')}}</td>
                </tr>
            @empty
                <strong></strong>
            @endforelse
            </tbody>
        </table>

        <h4>Allergies</h4>
        <table>
            <thead>
            <td>Description</td>
            {{--            <td>Date debut</td>--}}
            </thead>
            <tbody>
            <tr></tr>
            @forelse($consultationMedecine->dossier->allergies as $allergie)
                <tr>
                    <td>{{$allergie->description}}</td>
                    {{--                    <td>{{\Carbon\Carbon::parse($allergie->date)->format('d/m/Y')}}</td>--}}
                </tr>
            @empty
                <strong></strong>
            @endforelse
            </tbody>
        </table>

        <h4>Traitement actuel</h4>
        <table>
            <thead>
            <td>Description</td>
            <td>Date prescription</td>
            </thead>
            <tbody>
            <tr></tr>
            @foreach($consultationMedecine->dossier->traitements as $traiement)
                @if($loop->last)
                    <tr>
                        <td>{{$traiement->description}}</td>
                        <td>{{\Carbon\Carbon::parse($traiement->created_at)->format('d/m/Y')}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        <h4>Parametres</h4>
        @foreach($consultationMedecine->parametresCommun as $parametre)
            @if($loop->first)
                <p>Poids (kg) : {{$parametre->poids}} </p>
                <p>Taille (cm): {{$parametre->taille}}</p>
                <p>Bmi (kg/m²): {{$parametre->bmi}}</p>
                <p>TA Systolique (mmHg) : {{$parametre->ta_systolique}}</p>
                <p>TA Diastolique (mmHg) : {{$parametre->ta_diastolique}}</p>
                <p>Température (°C): {{$parametre->temperature}}</p>
                <p>Fréquence cardiaque (bpm) : {{$parametre->frequence_cardiaque}}</p>
                <p>Fréquence respiratoire (cpm) : {{$parametre->frequence_respiratoire}}</p>
                <p>sato2 (%) : {{$parametre->sato2}}</p>
            @endif


        @endforeach

        <h4>Examen(s) clinique(s)</h4>
        <p>{{$consultationMedecine->examen_clinique}}</p>

        <h4>Examen(s) complémentaire(s)</h4>
        <p>{{$consultationMedecine->examen_complementaire}}</p>

        <h4>Diagnostic</h4>
        @if(!is_object(collect($consultationMedecine->conclusions->toArray())->first()))
            @if(!is_null($consultationMedecine->conclusions->first()))
                <p>{{($consultationMedecine->conclusions->first())->description}}</p>
            @endif
        @endif

        <h4>Conduite à tenir</h4>
        <p>{{$consultationMedecine->traitement_propose}}</p>
    </div>
@endisset

@isset($resultatLabo)
@endisset

@isset($resultatImagerie)
@endisset

<p style="text-align: right"> Date de création : {{\Carbon\Carbon::parse()->format('d/m/Y')}}</p>
@isset($signature)
    @if(!is_null($signature))
        <div>
            <img  style="float: right" width="300px" height="300px" src={{public_path('/storage/'.$signature)}} />
        </div>
    @endif
@endisset
</body>
</html>