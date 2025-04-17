<p>Bonjour,</p>
<p>Nous vous informons que {{$civilite}} {{$nom}} a souscrit chez vous.</p>
<p>Vehicule enregistre {{$marque}} {{$model}} {{$vin}} pour une periode de {{$dure}} du {{$effet}} au {{$echeance}} .</p>
<p>Connectez vous pour voir le details en cliquant <a href="{{env('APP_URL')}}/detail-talon/{{$id_client}}/{{$id_talon}}">ici</a>.</p>

<b>Numero de police: {{$police}}</b><br>
<b>Montant payer: {{$prime}} FCFA</b><br>

<img style="width: 300px; height: auto;" src="{{ $message->embed(public_path('storage/image/logo/company/Qq7HsEy-logo.jpg')) }}" alt="Logo Platine assurances" /><br>


<b>Platine assurances Sénégal</b><br>
<b>Téléphone :+221338588738 / Portable: +221771125050</b><br>
<b>E-mail :contact@platineassurances.sn</b><br>
<b>Adresse : Route de l'aéroport virage en face église St Christophe, DAKAR - Sénégal</b>


