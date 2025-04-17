<p>Bonjour {{$civilite}} {{$nom}},</p>
<p>Nous avons remarqués que votre souscription pour une assurance automobile auprès de Platine assurances n'est pas arrivé à terme.</p>
<p>Vous pouvez la finalisé: <a href="{{env('APP_URL')}}/facture/{{$id_client}}/{{$id_talon}}">ici</a> </p>


<img style="width: 300px; height: auto;" src="{{ $message->embed(public_path('storage/image/logo/company/Qq7HsEy-logo.jpg')) }}" alt="Logo Platine assurances" /><br>

<b>Cordialement</b><br>
<b>Platine assurances Sénégal</b><br>
<b>Téléphone :+221338588738 / Portable: +221771125050</b><br>
<b>E-mail :contact@platineassurances.sn</b><br>
<b>Adresse : Route de l'aéroport virage en face église St Christophe, DAKAR - Sénégal</b>