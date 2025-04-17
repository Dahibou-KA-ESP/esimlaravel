<!DOCTYPE html>
<html lang="zxx">
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/images/platine.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/platine.png')}}" type="image/x-icon">
    <title>Assurpro-comparateur</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap_pdf.min.css') }}">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
</head>

<style>
    #pdf {
        width: 210mm; /* Largeur de la page A4 */
        height: 297mm; /* Hauteur de la page A4 */
        page-break-inside: avoid !important; /* Empêcher le contenu de se diviser sur plusieurs pages */
    }

    body {
    background: #fff;
    font-size: 0.8em;
    }
  
    h6{font-size:1em;}

    .button{
        text-align: center;
    }

    .container {
    width: 21cm;
    max-height: 40.7cm;
    }

    .invoice {
    background: #fff;
    width: 100%;
    padding: 50px;
    }

    .logo {
    width: 4cm;
    
    }

    .entete {
    width:200%;
    height: 6%;
    margin-left: 50px;
    }

    .document-type {
    text-align: right;
    font-size: 1.9em;
    font-weight: 700;
    color: #444;
    }

    .conditions {
    font-size: 0.95em;
    color: #666;
    }

    .bottom-page {
    font-size: 0.7em;
    text-align: center;
    }
</style>

<body>
    <div class="button">
        <a class="btn btn-info" href="{{ url('detail-talon/' . $package->client->id . '/' . $package->id_talon) }}">Retour</a>
        <button class="btn btn-primary" onclick="SaveToPDF()">Telecharger</button>
    </div >
   

    <div id="pdf" class="container" >
        <div class="row justify-content-between" style="display: flex; align-items: center;">
            <div class="col-2">
                <img class="entete" src="{{ asset('storage/image/logo/company/Qq7hseY-logo.jpg') }}" />
            </div>
        
            <div class="col-8">
                <h2 class="text-right" style="font-family: serif;font-size:30px;margin-right:50px;margin-top:30px;color:#210a65;border-bottom:3px solid #5c1245">Platine Assurances SA</h2>
            </div>
        </div>
        
        <div class="invoice" >
            <div class="row">
                <div class="col-7">
                    <p class="addressMySam">
                        <strong>Platine Assurances</strong><br/>
                        contact@platineassurances.com<br/>
                        Tel: +221338588738 / Portable: +221771125050 <br/>
                        Route de l'aéroport virage en face église St Christophe<br/>
                        DAKAR - Sénégal
                    </p>
                </div>
                <div class="col-5">
                    @if($package->statut == 1)
                        <h6 style="font-size: 1.5em !important;" class="document-type display-3">Conditions particulieres</h6>
                        <p class="text-right">Nº: {{$package->n_police}}</p>
                    @endif
                    @if($package->statut == 0)
                        <h1 class="document-type display-4">Devis</h1>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-7">
                    <div class="logo-table mb-2">
                        <img   style="width:100px;height:auto"src="{{asset('/storage/image/logo/' .$package->compagnie->logo);}}"   class="logo img-fluid2"/>
                    </div>                
                </div>
                <div class="col-5">
                    <p class="addressDriver text-right">
                        <strong >Client</strong><br/>
                        <!-- Réf. Client <em th:text="${driver.getUserId()}">Référence client</em><br/> -->
                        <span >{{$package->client->civilite}}{{$package->client->name}}</span><br/>
                        <span >{{$package->client->email}}</span><br/>
                        <span >{{$package->client->contact}}{{$package->client->phone}}</span> <br/>
                        <span>{{$package->client->adrss}}</span>
                    </p>
                </div>
            </div>
            <h6 >Fait à Dakar le: <span>{{$package->date_etablissement}}</span></h6>
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                <th>Véhicule</th>
                    <th>Immatriculation</th>
                    <th>Nbre de place</th>
                    <th>Puissance fiscale</th>
                    <th>Date de mise en circulation</th>
                    <th>Durée</th>
                    <th class="text-right">Validité</th>
                    <!-- <th>Taxe</th> -->
                    <!-- <th class="text-right" style="text-align:right">Total Nette</th> -->
                    <!-- <th class="text-right">Total TTC</th> -->
                </tr>
            
                </thead>
                <tbody>
                <tr>
                <td>{{$package->marque}} {{$package->model}}</td>
                    <td>{{$package->vin}}</td>
                    <td>{{$package->nombre_place}}</td>
                    <td>{{$package->force_fiscale}} CV</td>
                    <td>{{$package->date_first_circ}}</td>
                    <td>{{$package->duree}}</td>
                    <td class="text-right">{{$package->date_effet}} au {{$package->date_echeance}}</td>
                    <!-- <td>14%</td> -->
                    <!-- <td>{{$package->prime_net}} Fcfa</td> -->
                </tr>
                </tbody>
            
            </table>
            
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                    <th scope="col">Garantie(s)</th>
                    <th class="text-right" scope="col">Prime(s)</th>
                    
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>
                            <td>@php
                                $garantieNames = [];
                                foreach($package->produits as $item) {
                                    $garantieNames[] = $item->garantie->nom_garantie;
                                }
                                echo implode(', ', $garantieNames);
                            @endphp
                            </td>
                            <td class="text-right">@foreach($package->produits as $item)
                                    {{ number_format($item->pivot->prix, 0, ',', ' ') }} F CFA
                                    @if (!$loop->last) <!-- Vérifie si ce n'est pas le dernier élément -->
                                        + <!-- Ajoute une virgule si ce n'est pas le dernier élément -->
                                    @endif
                                @endforeach</td>  
                        </tr>
                    
                </tbody>
            </table>
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th >Prime Nette</th>
                    <th >Coût de police</th>
                    <th>Taxes (14%)</th>
                    <th >FGA</th>
                    <th>Primes TTC</th>
                </tr>
            
                </thead>
                <tbody>
                <tr>
                    <td>{{ number_format($package->prime_net, 0, ',', ' ') }} F CFA</td>
                    <td>{{ number_format($package->coup_police, 0, ',', ' ') }} F CFA</td>
                    <td>{{ number_format($package->taxe, 0, ',', ' ') }} F CFA</td>
                    <td>{{ number_format($package->fga, 0, ',', ' ') }} F CFA</td>
                    <td>{{ number_format($package->prime_ttc, 0, ',', ' ') }} F CFA</td>

                </tr>
                </tbody>
            
            </table>
            @foreach($package->produits as $item)
            @if($item->garantie->nom_court === "sr")
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    
                    <th style="width:300px;">Capitaux</th>
                    <th style="width:200px;">Franchises</th>
                </tr>
            
                </thead>
                <tbody>
                <tr>
                    <td>
                        @foreach($package->produits as $item)
                            @if($item->garantie->nom_court=== "sr")
                            <b>Sécurité routière:</b> décès {{ number_format($package->capital_dece, 0, ',', ' ') }} F CFA, invalidité {{ number_format($package->capital_invalidite, 0, ',', ' ') }} F CFA, frais médicaux: {{ number_format($package->frais_medicaux, 0, ',', ' ') }} F CFA<br>
                            @endif
                            @if($item->garantie->nom_court=== "vol")
                                <b>Incendie et vol:</b> {{ number_format($package->valeur_venale, 0, ',', ' ') }} F CFA <br>
                            @endif
                            @if($item->garantie->nom_court=== "ar")
                            <b>Avance sur recours:</b>{{ number_format($package->capitale_assure, 0, ',', ' ') }} F CFA <br>@endif
                            @if($item->garantie->nom_court=== "ar")
                            <b>Tierce complète:</b> {{ number_format($package->valeur_neuf, 0, ',', ' ') }} F CFA @endif 
                        @endforeach
                    </td> 
                    <td>
                        @foreach($package->produits as $item)
                            @if($item->garantie->nom_court=== "br")
                            <b>Bris de glace:</b> {{ number_format($package->franchise_bdg, 0, ',', ' ') }} F CFA<br>@endif
                            @if($item->garantie->nom_court=== "vol")
                            <b>Vol:</b> {{ number_format($package->franchise_vol, 0, ',', ' ') }} F CFA<br>@endif
                            @if($item->garantie->nom_court=== "tcmp")
                            <b>Tierce complete:</b> {{ number_format($package->franchise_tr, 0, ',', ' ') }} F CFA @endif
                        @endforeach
                    </td>
                </tr>
                </tbody>
            
            </table>
            @endif
            @endforeach
            <div class="row">
                <div class="col-12">
                    <p class="conditions" >
                                Le présent contrat est conclu pour la période indiquée ci-aprés,
                                <b> du {{$package->date_effet}} au {{$package->date_echeance}} à 23h:59.</b> 
                                Il cessera de plein droit et sans autre avis à la fin de cette période.
                    </p>
                </div>
            </div>
            <div class="row justify-content-end px-5">
                <div class="col-6">
                    <span class="h6">L'asssureur</span>
                </div>
                <div class="col-6 ">
                    <span class="h6 " style="float:inline-end;">L'asssuré</span>
                </div>
            </div>


            <div style="position: absolute; bottom: 0; width: 85%;">
                <div>
                    <h6 style="border: 2px solid #210a65; padding: 2px;background-color: #210a65;color:white;text-align:center;" >S.A au capital social de 10.000.000 FCFA -RC: SN DKR 2022 B 15554 - NINEA: 009389207 </h6>
                </div>
                <div class="row">
                    <div class="col-6">
                       <h6 style="color:#210a65"> Adresse: Route de l'aéroport virage en face église St Christophe DAKAR - Sénégal</h6>
                       <h6 style="color:#210a65">Téléphone: 33 858 87 38 - Portable: 77 112 50 50  </h6>
                    </div>
                    <div class="col-6 text-right">
                        <h6 style="color:#210a65">email: contact@platineassurances.sn</h6>
                       <h6 style="color:#210a65">site web: www.platineassurances.sn</h6>
                    </div>
                </div>
            </div>
            
        </div>
    </div>


</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script> 
<script>
     function SaveToPDF() {
        var element = document.getElementById('pdf');
        var clientName = '{{$package->client->name}}';
        var clientN_police = '{{$package->n_police}}';
        var options = {
        filename: 'Facture_' + clientName +'_'+ clientN_police + '.pdf', // Nom du fichier PDF
        jsPDF: { // Options de jsPDF
            format: 'a4', // Format de page A4
        }
    };
    
    html2pdf()
        .from(element)
        .set(options) // Définir les options du PDF
        .toPdf() // Convertir en PDF
        .get('pdf')
        .then(function(pdf) {
            pdf.deletePage(2); // Supprimer toutes les pages après la première
        })
        .save();
    }
</script>


