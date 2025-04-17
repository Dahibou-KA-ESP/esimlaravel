<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="pixelstrap"> -->
        <link rel="icon" href="{{asset('assets/images/platine.png')}}" type="image/x-icon">
        <link rel="shortcut icon" href="{{asset('assets/images/platine.png')}}" type="image/x-icon">

        <title>Assurpro-comparateur</title>
        <!-- Google font-->
        <!-- <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet"> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>    
        
        <style>
            table{
            border-collapse: collapse;
            justify-content: center !important;
            width: 98.5%;
            margin-left: 0.75%;
            }

            th, td{
                
            border: 1px solid black;
            padding: 10px;
            height: 1px;
            text-align: center;

            }
            .body {
                display: flex;
                flex-direction: column;
            }

            .row {
                display: flex;
            }

            .pair {
                display: flex;
                margin: 5px 10px;
                justify-content: center !important;
                margin-right: 2%;
                
            } .pairs {
                margin: 0 20px;
                justify-content: center !important;
                margin-right: 80%;
            }

            .body label {
                text-align: center;
                margin-right: 10px;
                height: 30%;

            }

            .body input {
                width: 100%;
                height: 70%;
            }

            @media only screen and (min-width: 600px) {
                .pair {
                    width: 45%;
                }
            }
        </style>

    </head>

    <body>
        <div class="content">
            <div class="header"></div>
            <div id="pdf" class="body">
                <div style="display: flex;">
                    <img style="width: 130px;" src="{{ asset('assets/images/platine.png') }}" name="logo">
                    <img style="width: 150px;height:50px; margin-left: auto;" src="{{asset('/storage/image/logo/' .$package[0]->logo);}}"  name="logo1" alt="{{$package[0]->compagnie}}">
                </div> 
                <h5 style="border: 1px solid black;text-align:center;margin:10px">CONDITIONS PARTICULIERES</h5>
                <h7 style="margin:10px">Voitures Particulieres</h7>
                    <div class="row">
                        <div class="pair">
                            <label>Intermediaire</label>
                            <input value="PLATINE ASSURANCES/">
                        </div>
                        <div class="pair">
                            <label>Contrat_n</label>
                            <input value="rc/vrr98">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                            <label>Assuré</label>
                            <input value="{{$package[0]->client->name}}">
                        </div>
                        <div class="pair">
                            <label>Mouvement</label>
                            <input value="affaire nouvelle">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Addresse</label>
                                <input value="{{$package[0]->client->adrss}}">
                            </div>
                            <div class="pair">
                                <label>A_effet_du</label>
                                <input value="{{$package[0]->date_effet}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Assureur</label>
                                <input value="{{$package[0]->compagnie}}">
                            </div>
                            <div class="pair">
                                <label>Echeance</label>
                                <input value="{{$package[0]->date_echeance}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Durée</label>
                                <input value="{{$package[0]->duree}}">
                        </div>
                        <div class="pair">
                                <label></label>
                                
                            </div>
                    </div>
                <h5 style="border: 1px solid black;text-align:center;margin:10px;">Information Concernant l'assuré(e)</h5>
                    <div class="row">
                        <div class="pair">
                            <label>Civilité</label>
                            <input value="{{$package[0]->client->civilite}}">
                        </div>
                        <div class="pair">
                            <label>Email</label>
                            <input value="{{$package[0]->client->email}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                            <label>Nom_&_Prenom</label>
                            <input value="{{$package[0]->client->name}}">
                        </div>
                        <div class="pair">
                            <label>Telephone</label>
                            <input value="{{$package[0]->client->phone}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Profession</label>
                                <input value="{{$package[0]->client->profession}}">
                            </div>
                            <div class="pair">
                                <label></label>
                                
                            </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Adresse_geographique</label>
                                <input value="{{$package[0]->client->adrss}}">
                            </div>
                            <!-- <div class="pair">
                                <label>N attestation Jaune</label>
                                <input value="SN524GJ">
                            </div> -->
                            <div class="pair">
                                <label>N_Police</label>
                                <input value="{{$package[0]->n_police}}">
                            </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Ville</label>
                                <input value="{{$package[0]->client->ville}}">
                        </div>
                        <div class="pair">
                                <label>N_attestation_CEDEAO</label>
                                <input value="">
                        </div>
                    </div>
                <h5 style="border: 1px solid black;text-align:center;margin:10px;">Information Concernant le vehicule</h5>
                    <div class="row">
                        <div class="pair">
                            <label>Marque</label>
                            <input value="{{$package[0]->marque}}">
                        </div>
                        <div class="pair">
                            <label>Modele</label>
                            <input value="{{$package[0]->model}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                            <label>Immatriculation/chassis</label>
                            <input value="{{$package[0]->vin}}">
                        </div>
                        <div class="pair">
                            <label>Mise_en_circulation</label>
                            <input value="{{$package[0]->date_first_circ}}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="pair">
                            <label>Energie</label>
                            <input value="{{$package[0]->force_fiscale}}">
                        </div>
                        <div class="pair">
                                <label>Puissance</label>
                                <input value="{{$package[0]->force_fiscale}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Nombre_de_place</label>
                                <input value="{{$package[0]->nombre_place}}">
                        </div>
                        <div class="pair">
                            <label>Usage</label>
                            <input value="{{$package[0]->type_carrosserie}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                                <label>Carrosserie</label>
                                <input value="{{$package[0]->type_carrosserie}}">
                        </div>
                        <div class="pair">
                            <label>Date_Immatriculation</label>
                            <input value="12/02/2014">
                        </div>
                    </div>
                    <div class="row">
                        <div class="pair">
                            <label>Valeur_neuf</label>
                            <input value="{{$package[0]->valeur_neuf}}">
                        </div>
                        <div class="pair">
                            <label>Valeur_Vénale</label>
                            <input value="{{$package[0]->valeur_venale}}">
                        </div>
                    </div>
                    <h5 style="border: 1px solid black;text-align:center;margin:10px;">Garantie(s) et prime respective(s)</h5>
                    
                    <div class="">
                        <table>
                            <thead>
                                <tr>
                                <th scope="col">Garantie(s)</th>
                                <th scope="col">Prime(s)</th>
                                
                                </tr>
                            </thead>
                            @foreach($package as $item)
                            <tbody>
                            @foreach(explode(',', $item->garantie) as $key => $garantie)
                                    <tr>
                                        
                                        <td>
                                        {{$garantie}}<br>
                                        </td>
                                        @foreach($package as $item)
                                        @php
                                            $prix_garanties = explode(',', $item->prix_garantie);
                                        @endphp
                                        <td>{{$prix_garanties[$key]}}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    
                                
                            </tbody>
                            @endforeach
                        </table>
                    </div>

                    <h5 style="border: 1px solid black;text-align:center;margin:10px;">Detail de la prime</h5>

                    <div class="">
                        <table>
                            <thead>
                                <tr>
                                <th scope="col">Prime nette HT</th>
                                <th scope="col">Accesoire</th>
                                <th scope="col">Taxe</th>
                                <th scope="col">Fga</th>
                                <th scope="col">Prime TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                <td>{{$package[0]->prime_net}}</td>
                                <td>{{$package[0]->coup_police}}</td>
                                <td>{{$package[0]->taxe}}</td>
                                <td>{{$package[0]->fga}}</td>
                                <td>{{$package[0]->prime_ttc}}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <h7 style="margin:10px">Bonus commerciale 20%</h7>

                    <h5 style="border: 1px solid black;text-align:center;margin:10px;margin-top:2px;">Clauses applicable</h5>
                    <div class="row"><br>
                        <div class="pairs">
                            <h7>Le present contrat est conclu pour la periode indiquée ci-dessus.</h7><br>
                            <h7>Il cesser de plein droit et sans aute avis a la fin de</span></p>
                        </div>
                    </div>
                    <div class="row"><br>
                        <div class="pairs">
                            <h5 style="display: flex;text-decoration: underline;"  >Pour l'assuré</h5>
                        </div>
                        <div class="pairs">
                            <p id="date">Fait à Dakar le, <span id="dateJour"></span></p>
                            <h5 style="display: flex;text-decoration: underline;" class="text-end" >Pour l'assureur</h5>
                        </div>
                    </div>
            </div>
            <div class="footer"></div>
        </div><br>

        <a href="{{ url('detail-talon/' . $package[0]->client->id . '/' . $package[0]->id_talon) }}">Retour</a>
        <button onclick="SaveToPDF()">Telecharger</button>
    </body>

</html>

<script>

    function SaveToPDF() {
            var element = document.getElementById('pdf');
            html2pdf()
                .from(element)
                .save();
    }

    function printToPDF() {
        var element = document.getElementById('pdf');
    html2pdf()
        .from(element)
        .toPdf()
        .output();
    }
    
    // function printToPDF() {
    //     var element = document.getElementById('pdf');
    //     html2pdf()
    //         .from(element)
    //         .toPdf()
    //         .get('pdf')
    //         .then(function (pdf) {
    //             var link = document.createElement('a');
    //             link.href = URL.createObjectURL(pdf.output('blob'));
    //             link.download = 'document.pdf';
    //             link.click();
    //         });
    // }

    // Obtenez la date actuelle
    var date = new Date();
    
    // Obtenez le jour, le mois et l'année
    var jour = date.getDate();
    var mois = date.getMonth() + 1; // Les mois commencent à partir de 0, donc nous ajoutons 1
    var annee = date.getFullYear();

    // Formatez la date dans le format souhaité (jour/mois/année)
    var dateFormatee = jour + '/' + mois + '/' + annee;

    // Affichez la date dans votre paragraphe
    document.getElementById('dateJour').innerHTML = dateFormatee;

    // Fonction pour générer le PDF
    function generatePdf() {
            var doc = new jsPDF();
            var htmlContent = document.documentElement.innerHTML;

            doc.html(htmlContent, {
                callback: function (doc) {
                    // Télécharger le PDF une fois généré
                    doc.save('document.pdf');
                }
            });
        }

        // Appeler la fonction pour générer le PDF
        generatePdf();
</script>
