<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,500i,600,700,800,900&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900&display=swap' rel='stylesheet'>

    <title>Prescription imageries de {{ $patient->user->name }} du {{ $date }} par {{ $medecin->civilite ?? '' }} {{ $medecin->user->name }}</title>

    <style>
        body {
            font-size: 0.9em;
            line-height: 1.2;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1.2px;
            color: #32325d;
            background-color: white;
        }

        h2 {
            color: #00ada7;
            font-weight: 600;
            text-align: left;
            font-size: 2em !important;
        }
        p{
            font-weight: 500;
        }
        h3,
            /*b {*/
            /*    color: #dee2e6;*/
            /*}*/

        td,
        th,hr {
            border: 1px solid #dee2e6;
            padding: 0.2em;

        }
        hr {
            border: 1px solid #dee2e6;
        }
        th{
            font-weight:700;
        }

        table {
            border-collapse: collapse;
        }
        .titre-rapport {
            text-align: center;
            text-transform: uppercase;
            color:#00ada7;
            font-weight: 900;
        }
        .sous-titre-rapport {
            text-transform: uppercase;
            color:#00ada7;
            /*font-size:0.8em;*/
        }
        .titre{
            text-transform: uppercase;
            color:#00ada7;
        }

        .sous-titre-rapport::after{
            /* content:""; */
            display:block;
            width:60%;
            /* height:0.5px; */
            font-weight: 600;
            /* background-color:#dee2e6; */
            /* margin:0 auto;*/
            margin-top:3em;
        }

        .logo-rapport{
            width:200px;
            heigth: auto;
        }

        .rapport-logo-wrapper{
            margin-left:39%;

        }
        .title-table{
            font-weight : 600;

        }
        .sous-titre-rapport--table{
            text-transform: uppercase;
            color:#00ada7;
            font-size:0.8em;
        }
        /* DivTable.com */
        .divTable {
            display: table;
            width: 100%;
        }

        .divTableRow {
            display: table-row;
        }

        .divTableHeading {
            /*background-color: #eee;*/
            display: table-header-group;
        }

        .divTableCell,
        .divTableHead {
            /*border: 1px solid #999999;*/
            display: table-cell;
           /* padding: 3px 10px;*/
        }

        .divTableHeading {
            /*background-color: #eee;*/
            display: table-header-group;
            font-weight: bold;
        }

        .divTableFoot {
            /*background-color: #eee;*/
            display: table-footer-group;
            font-weight: bold;
        }

        .divTableBody {
            display: table-row-group;
        }
        .column {
            float: left;
            width: 50%;
        }
        .row{
            border-bottom: 1px solid #dee2e6;
        }
        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
            width: 100%;
        }
        .row p{
            font-size: 14px;
        }
        .p{
            font-size: 14px;
        }
    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
    @endphp

    <div class="justify-content-center">
        <h2>{{ $patient->user->name }}</h2>
        <h3>{{ Carbon::parse($patient->date_de_naissance)->locale(config('app.locale'))->translatedFormat('jS F Y') }}</h3>
        <h3>Sexe : {{ $patient->sexe == "M" ? "Masculin" : "Féminin" }}</h3>
    </div>

    <h4><span class="titre">Prescipteur:</span> <b>{{ $medecin->civilite ?? '' }}  {{ $medecin->user->name }}</b> </h4>
    @if(count($prescription_imagerie['etablissements']) > 0)
        <h4><span class="titre">Etablissement:</span> <b>{{ $prescription_imagerie['etablissements'][0]['name'] }}</b> </h4>
    @endif

    <h4 class="sous-titre-rapport">Informations cliniques pertinentes</h4>
    <p>{!! $prescription_imagerie['information_clinique'] !!}</p>

    <h4 class="sous-titre-rapport">Explication de la demande de diagnostic</h4>
    <p>{!! $prescription_imagerie['explication_demande_diagnostic'] !!}</p>

    @if(count($prescription_imagerie['information_supplementaires']) > 0)
        <h4 class="sous-titre-rapport">Informations supplémentaires pertinentes</h4>
        <ol>
            @forelse ($prescription_imagerie['information_supplementaires'] as $information)
                <li>{{ $information['libelle'] }}</li>
            @empty
            @endforelse
        </ol>
    @endif 


    @if (count($prescription_imagerie['examen_complementaires'])>0)
        <h4 class="sous-titre-rapport">Examens à réaliser</h4>
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Catégorie</th><th>Examens</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prescription_imagerie['type_examens'] as $type_examen)
                    <tr>
                        <td rowspan="{{ count($type_examen['examen_complementaires']) }}">{{ $loop->iteration }}</td>
                        <td rowspan="{{ count($type_examen['examen_complementaires']) }}">{{ $type_examen['libelle'] }}</td>
                        @forelse ($type_examen['examen_complementaires'] as $item)
                            <td>{{ $item['fr_description'] }}</td></tr>
                            @if(!$loop->last)
                                <tr>
                            @endif
                        @empty
                        @endforelse
                    </tr>
                @empty
                @endforelse
            </tbody>

        </table>
    @endif


    @if(count($prescription_imagerie['examens_pertinents']) > 0)
        <h4 class="sous-titre-rapport">Examens pertinents précédents relatifs à la demande de diagnostic</h4>
        <ol>
            @forelse ($prescription_imagerie['examens_pertinents'] as $examens_pertinent)
                <li>{{ $examens_pertinent['libelle'] }}</li>
            @empty
            @endforelse
        </ol>
    @endif 



 {{--    <p> Honorée Consoeur, Honoré Confrère, bonjour</p>
    <p>Voudriez-vous prendre contact avec {{ $patient->sexe == "M" ? "monsieur" : "madame" }} {{ $patient->user->name }} Patient{{ $patient->sexe == "M" ? "" : "e" }} né{{ $patient->sexe == "M" ? "" : "e" }} le {{ Carbon::parse($patient->date_de_naissance)->locale(config('app.locale'))->translatedFormat('jS F Y') }}, résidant à {{ $patient->user->ville }} – {{ $patient->user->pays }}
        @if(count($prescription_imagerie['teleconsultations']) > 0)
            , en vue d'une consultation de {{ $prescription_imagerie['teleconsultations'][0]['type']['libelle'] }} le {{ Carbon::parse($prescription_imagerie['created_at'])->format('d-M-Y') }}
        @endif
    </p>
    <p>Contact Patient{{ $patient->sexe == "M" ? "" : "e" }} : <a href="tel:+{{$patient->user->telephone}}">{{ number_format($patient->user->telephone, 0,","," ")  }}</a>  </p>

 --}}

    {{-- @if(count($prescription_imagerie['motifs']) > 0)
        <h4 class="sous-titre-rapport">Motifs de consultations</h4>
        <ol>
            @forelse ($prescription_imagerie['motifs'] as $motif)
                <li>{{ $motif['description'] }}</li>
            @empty 
            @endforelse
        </ol>
    @endif --}}

   {{--  <h4 class="sous-titre-rapport">Niveau d'urgence: {{ $prescription_imagerie['niveau_urgence']['id'] }}</h4>
    <p>{{ $prescription_imagerie['niveau_urgence']['description'] }}</p>

    @if(count($prescription_imagerie['option_financements']) > 0)
        <h4 class="sous-titre-rapport">Options de financements</h4>
        <ol>
            @forelse ($prescription_imagerie['option_financements'] as $option_financement)
                <li>{{ $option_financement['libelle'] }}</li>
            @empty 
            @endforelse
        </ol>
    @endif

    @if(count($prescription_imagerie['raison_prescriptions']) > 0)
        <h4 class="sous-titre-rapport">Raison de prescription</h4>
        <ol>
            @forelse ($prescription_imagerie['raison_prescriptions'] as $raison_prescription)
                <li>{{ $raison_prescription['libelle'] }}</li>
            @empty 
            @endforelse
        </ol>
    @endif --}}

    {{-- <h4 class="sous-titre-rapport">Plainte</h4>
    <p>{{ $prescription_imagerie['plainte'] }}</p> --}}

   {{--  @if (count($prescription_imagerie['examen_complementaires'])>0)
        <h4 class="sous-titre-rapport">Examens complémentaires pouvant être réalisés si la Clinique le justifie:</h4>
        <div class="row">
            <ol>
                @forelse ($prescription_imagerie['examen_complementaires'] as $examen_complementaire)
                    <li>{{ $examen_complementaire['fr_description'] }}</li>
                @empty
                @endforelse
            </ol>
        </div>
    @endif
    <p>Bien vouloir nous présenter votre projet diagnostique complémentaire et thérapeutique s’il vous plait.</p>
    <p>Voudriez-vous nous transmettre par la suite votre rapport de consultation à l’adresse <a href="mailto:medical@medicasure.com">medical@medicasure.com</a> .</p>
    <p>Je vous remercie d’avance de votre diligence.</p>
    <p>Sincères salutations</p>
    <p>NB: En cas d’urgence médicale, prière de prendre contact téléphonique avec le médecin referent. </p>
 --}}

    <div>
        <p>
            Date de prescription: {{ Carbon::parse($prescription_imagerie['date_heure'])->format('d-m-Y à H:i') }} <br>
            Numéro d'ordre: {{ $medecin->numero_ordre }} <br>
            Téléphone:  {{ number_format($medecin->user->telephone, 0,","," ")  }} <br>
        </p>
        <p></p>
        @isset(explode('storage', $medecin->user->signature)[1])
            <div>
                <img style="width: 270px;"  src="{{ public_path('/storage/'.explode('storage', $medecin->user->signature)[1]) }}" />
            </div>
        @endisset
    </div>


</body>
</html>
