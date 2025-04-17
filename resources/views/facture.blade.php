<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Votre facture</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap_pdf.min.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>    
    
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

  
    .logo-table {
        width: 70px;
        height: 70px;
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
    .img-fluid2 {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .card-front__heading {
    font-size: 1.5rem;
    margin-top: .25rem;
    }

    .cards-list {
    z-index: 0;
    width: 100%;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    }

    .card {
    margin: 20px auto;
    width: 200px;
    height: 200px;
    border-radius: 40px;
    box-shadow: 5px 5px 30px 7px rgba(0,0,0,0.25), -5px -5px 30px 7px rgba(0,0,0,0.22);
    cursor: pointer;
    transition: 0.4s;
    }

    .card .card_image {
    width: inherit;
    height: inherit;
    border-radius: 40px;
    }

    .card .card_image img {
    width: inherit;
    height: inherit;
    border-radius: 40px;
    object-fit: cover;
    }

    .card .card_title {
    text-align: center;
    border-radius: 0px 0px 40px 40px;
    font-family: sans-serif;
    font-weight: bold;
    font-size: 20px;
    margin-top: -50px;
    height: 100%;
    color:white;
    }

    .card:hover {
    transform: scale(0.9, 0.9);
    box-shadow: 5px 5px 30px 15px rgba(0,0,0,0.25), 
        -5px -5px 30px 15px rgba(0,0,0,0.22);
    }

    .title-white {
    color: white;
    }

    .title-black {
    color: black;
    }

    @media all and (max-width: 500px) {
    .card-list {
        /* On small screens, we are no longer using row direction but column */
        flex-direction: column;
    }
    }

    @media screen and (max-width: 1332px) {
        .pdf2{
            display:none;
        }
    }
    /*
    .card {
    margin: 30px auto;
    width: 300px;
    height: 300px;
    border-radius: 40px;
    background-image: url('https://i.redd.it/b3esnz5ra34y.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-repeat: no-repeat;
    box-shadow: 5px 5px 30px 7px rgba(0,0,0,0.25), -5px -5px 30px 7px rgba(0,0,0,0.22);
    transition: 0.4s;
    }
    */
    .montant {
    font-family: 'Bebas Neue', cursive;
    font-size: 3em;
        color:#210a65;
    transform: translate(-50%,-50%);
    background-image: linear-gradient(#5c1245, #5c1245);
    background-size: 100% 10px;
    background-repeat: no-repeat;
    background-position: 100% 0%;
        transition: background-size .7s, background-position .5s ease-in-out;
    }

    .montant:hover {
    background-size: 100% 100%;
    color:white;
    background-position: 0% 100%;
    transition: background-position .7s, background-size .5s ease-in-out;
    }
    .payez{
        color:#210a65;
        border-bottom:2px solid #210a65;
    }
</style>

<body>
    <div class="row align-items-start justify-content-center ">
        <div class="col-md-6 mt-5">          
            <div class="button sticky-top">
                @auth
                    <a class="btn text-white mb-1" href="https://comparateurtest.assurpro.sn/admin/simulateur/pack" style="background-color:#5c1245;width:250px;">Nouvelle simulation</a>
                    @else
                    <a class="btn text-white mb-1" href="https://platineassurances.sn/comparateur-auto" style="background-color:#5c1245;width:250px;">Nouvelle simulation</a>
                    @endauth
                @if($package->statut == 1)
                <button class="btn text-white mb-1" onclick="SaveToPDF()" style="background-color:#210a65;width:250px;">Telecharger votre facture</button>
                @endif
                @if($package->statut == 0)
                <button class="btn text-white mb-1" onclick="SaveToPDF()" style="background-color:#210a65;width:250px;">Telecharger votre devis</button>
                @endif
            </div>
            <div class="mt-3 text-center ">
                <span class="montant text-center">{{number_format($package->prime_ttc, 0, ',', ' ')}} Fcfa </span>
            </div>
            @if($package->statut == 0)
                <div class="mt-2  text-center ">
                    <span class="h4 payez">Payez avec:</span>
                </div>
                <div class="row mt-5 justify-content-center ">
                    <div class="cards-list">                   
                        <div class="card 3" onclick="payewave({{json_encode($package)}})">
                            <div class="card_image">
                                <img src="{{ asset('assets/images/payement/wave.gif') }}" class="img-fluid2"/>
                            </div>
                            <div class="card_title" style="background: #1AB3E5;">
                            <p>Wave</p>
                            </div>
                        </div>
                        
                        <div class="card 4" onclick="payeom({{json_encode($package)}})">
                            <div class="card_image">
                                <img src="{{ asset('assets/images/payement/om.gif') }}" class="img-fluid2"/>
                            </div>
                            <div class="card_title title-black" style=" background: #f16e00;">
                            <p>Orange Money</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($package->statut == 1)
                <div class="w-100 d-flex justify-content-center mt-5">
                    <div  style="width: 200px; height: 50px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200">
                        <!-- Border -->
                        <rect x="5" y="5" width="390" height="140" rx="20" ry="20" fill="none" stroke="	#22bb33" stroke-width="7"/>
                        
                        <!-- Text -->
                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="	#22bb33">Platine Assurances</text>
                        
                        <!-- "Payée" Text -->
                        <text x="50%" y="30%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="70" fill="	#22bb33">Payée</text>
                        </svg>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6 bg-dark p-2 pdf2" style="background:#6e6e6e !important; ">
            <div id="pdf"  class="container " style=" background:white;">
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
                                <h1 class="document-type display-4">FACTURE</h1>
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
                    <h6 >Fait à Dakar le: <span>{{$package->date_etablissement}}</span>  
                    </h6>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Compagnie</th>
                            <th>Véhicule</th>
                            <th>Puissance fiscale</th>
                            <th>Durée</th>
                            <th class="text-right">Validité</th>
                            <!-- <th>Taxe</th> -->
                            <!-- <th class="text-right" style="text-align:right">Total Nette</th> -->
                            <!-- <th class="text-right">Total TTC</th> -->
                        </tr>
                    
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$package->compagnie->nom_complet}}</td>
                            <td>{{$package->marque}} {{$package->model}}</td>
                            <td>{{$package->force_fiscale}} CV</td>
                            <td>{{$package->duree}}</td>
                            <td class="text-right">{{$package->date_effet}} au {{$package->date_echeance}}</td>
                            <!-- <td>14%</td> -->
                            <!-- <td>{{$package->prime_net}} Fcfa</td> -->
                        </tr>
                        </tbody>
                    
                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">Garantie(s)</th>
                            <th class="text-right" scope="col">Prime(s)</th>
                            
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($package->produits as $item)
                                <tr>
                                    <td>
                                        {{$item->garantie->nom_garantie}}<br>
                                    </td>
                                    <td class="text-right">{{number_format($item->pivot->prix, 0, ',', ' ')}} F CFA</td>  
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row mt-1">
                        <div class="col-8">
                            <p class="conditions" >
                                Le présent contrat est conclu pour la période indiquée ci-dessous.
                                <br/><b> du {{$package->date_effet}} au {{$package->date_echeance}} à 23h:59</b> <br/><br/>
                                Il cessera de plein droit
                                <br/>
                                et sans autre avis à la fin de cette période.
                                <!-- <br/>
                                Aucun escompte consenti pour règlement anticipé.
                                <br/>
                                Règlement par virement bancaire ou carte bancaire.
                                
                                En cas de retard de paiement, indemnité forfaitaire pour frais de recouvrement : 40 euros (art. L.4413
                                et
                                L.4416 code du commerce). -->
                            </p>
                        </div>
                        <div class="col-4">
                            <table class="table table-sm text-right" >
                                <tr>
                                    <td><strong>Prime nette</strong></td>
                                    <td class="text-right" >{{number_format($package->prime_net, 0, ',', ' ')}} F CFA</td>
                                </tr>
                                <tr>
                                    <td>Coût de police</td>
                                    <td class="text-right" >{{number_format($package->coup_police, 0, ',', ' ')}} F CFA</td>
                                </tr>
                                <tr>
                                    <td>Taxes (14%)</td>
                                    <td class="text-right" >{{number_format($package->taxe, 0, ',', ' ')}} F CFA</td>
                                </tr>
                                <tr>
                                    <td>FGA</td>
                                    <td class="text-right"  >{{number_format($package->fga, 0, ',', ' ')}} F CFA</td>
                                </tr>
                                <tr>
                                    <td><strong>Prime TTC</strong></td>
                                    <td class="text-right"  >{{number_format($package->prime_ttc, 0, ',', ' ')}} F CFA</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div style="position: absolute; bottom: 0; width: 82%;left: 68px;">
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
            </div>
        </div>
    </div>
</body>

</html>
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
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


        function payewave(select){
             var form = document.createElement("form");
                            form.method = "post";  // Set the form method to POST

                            // Add the CSRF token input
                            var csrfTokenInput = document.createElement("input");
                            csrfTokenInput.type = "hidden";
                            csrfTokenInput.name = "_token";
                            csrfTokenInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfTokenInput);

                            form.action = "{{ url('achat-wave') }}";

                            // Create an input element to hold your data
                            var input = document.createElement("input");
                            input.type = "hidden";  // Hidden input for POST data
                            input.name = "result";  // Set the name of the input
                            input.value = JSON.stringify(select);  // Set the value

                            // Append the input to the form
                            form.appendChild(input);

                            // Append the form to the body
                            document.body.appendChild(form);

                            // Submit the form
                            form.submit();
		};

        function payeom(select){
            const apiKey ='Z229M3D9iG5QgXbdbpPY8cjT86k2Tgb2j3G9b6kPXiDM8pY8QZdc95'
            var formdata = {
                client_id :select['client_id'],
                id_talon :select['id_talon'],
                _token: '{{ csrf_token() }}'
            }           
            $.ajax({
                url: '/api/achat-om',
                headers:{
                    'X-Api-Key': apiKey
                },
                type: 'POST',
                data: formdata,
                success: function (response) {
                    // console.log(response.paiement_om.qrCode)
                    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                    // Redirection en fonction du type d'appareil
                    if (isMobile) {
                        // Redirection vers le lien de paiement (DeepLink)
                        window.location.href = response.paiement_om.deepLink;
                    } else {
                        
                        var qrWindow = window.open("", "_self");
                           
                        // Contenu HTML pour la page du code QR
                        var qrContent = '<!DOCTYPE html>' +
                        '<html lang="en">' +
                        '<head>' +
                        '<meta charset="UTF-8">' +
                        '<meta name="viewport" content="width=device-width, initial-scale=1.0">' +
                        '<title>Paiement Orange Money vers Platine Assurance</title>' +
                        '<style>' +
                        'body { font-family: Arial, sans-serif; margin: 0; padding: 20px; text-align: center; background-color: #ff7900; }' +
                        'h1 { color: #ffffff; }' +
                        '.qr-container { display: inline-block; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; margin-bottom: 20px; }' +
                        'img { width: 100%; height: 100%; border-radius: 10px; }' +
                        '.caption { color: #ffffff; font-size: 20px; }' +
                        '.icon { width: 100px; height: 100px; fill: #ffffff; }' +
                        '</style>' +
                        '</head>' +
                        '<body>' +
                        '<h1>Paiement Orange Money vers Platine Assurance</h1>' +
                        '<div class="qr-container">' +
                            '<img src="data:image/png;base64,' + response.paiement_om.qrCode + '" alt="QR Code">' +
                        '</div>' +
                        '<div class="caption">' +
                            '<p>Veuillez scanner pour payer</p>' +
                            '<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100" height="100" fill="#ffffff">' +
                                '<path fill="none" d="M0 0h24v24H0V0z"/>' +
                                '<path d="M12 8c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm6 6.9V18c0 1.1-.9 2-2 2H8c-1.1 0-2-.9-2-2v-3.1C4.42 16.64 3.16 14.59 3 12c0-3.31 2.69-6 6-6s6 2.69 6 6c-.16 2.59-1.42 4.64-3 5.9zM9 17h6v-2c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2zm7-5c0-1.66-1.34-3-3-3s-3 1.34-3 3c0 1.31.84 2.41 2 2.82V17h4v-2.18c1.16-.41 2-1.51 2-2.82z"/>' +
                            '</svg>' +
                        '</div>' +
                        '</body>' +
                        '</html>';                    
                            qrWindow.document.write(qrContent);
                    }
                },
                error: function (error) {
                    console.error('Erreur lors de l\'envoi des données :', error);
                    // Utilisez SweetAlert pour afficher un message d'erreur
                }
            });
        }
</script>


