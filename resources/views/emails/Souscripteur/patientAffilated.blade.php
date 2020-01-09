@component('mail::message')
# Medicalink

Hello <strong>{{ucfirst($souscripteur->user->prenom).'  '.strtoupper($souscripteur->user->nom)}}</strong>,<br>
Nous espérons vous trouvez bien portant. Vous avez été désigné comme souscripteur du patient:<br>
Nom: <strong>{{ucfirst($patient->user->prenom).'  '.strtoupper($patient->user->nom)}}</strong><br>
Email: {{$patient->user->email}}

Thanks,<br>
{{ config('app.name') }}
<div class="div-logo-mail">
<img class="logo-footer" src="{{asset('/images/logo.png')}}" alt="Logo-Medicasure" height="50px" width="150px">
</div>
@endcomponent