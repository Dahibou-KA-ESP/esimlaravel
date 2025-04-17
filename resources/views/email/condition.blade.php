<p>Bonjour {{$nom}},</p>
<p>Nous vous remercions de la confiance et vous confirmons que votre souscription automobile N°{{$police}} du {{$effet}} au {{$echeance}} a été bien pris en compte.</p>
<p>Un conseiller commercial prendra contact avec vous afin de procéder à la livraison de votre attestation.</p>
<p>Retrouver votre facture définitive ainsi que les conditions particulières en cliquant <a href="{{env('APP_URL')}}/condition/{{$id_client}}/{{$id_talon}}">ici</a>  </p>

<img style="width: 300px; height: auto;" src="{{ $message->embed(public_path('storage/image/logo/company/Qq7HsEy-logo.jpg')) }}" alt="Logo Platine assurances" /><br>


<b>Cordialement</b><br>
<b>Platine assurances Sénégal</b><br>
<b>Téléphone :+221338588738 / Portable: +221771125050</b><br>
<b>E-mail :contact@platineassurances.sn</b><br>
<b>Adresse : Route de l'aéroport virage en face église St Christophe, DAKAR - Sénégal</b>   