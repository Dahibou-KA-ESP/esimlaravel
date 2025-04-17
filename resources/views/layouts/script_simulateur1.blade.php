<script>
        var Bonus_commercial = 0.20;
        var Taxe = 0.14;
        var Coup_Police = 3000;
        
        function changerOptions() {
            var carburantSelect = document.getElementById("energie");
            var modeleSelect = document.getElementById("force_fiscale");

            // Supprimer les options actuelles
            modeleSelect.innerHTML = "";

            // Ajouter de nouvelles options en fonction du carburant choisi
            if (carburantSelect.value === "essence") {
                var optionsEssence = ["2", "3-6", "7-10","11-14","15-23","24"];
                ajouterOptions(optionsEssence, modeleSelect);
            } else if (carburantSelect.value === "diesel") {
                var optionsDiesel = ["2", "3-4", "5-7","8-10","11-16","17"];
                ajouterOptions(optionsDiesel, modeleSelect);
            }
        }

        function ajouterOptions(options, selectElement) {
            for (var i = 0; i < options.length; i++) {
                var option = document.createElement("option");
                option.value = options[i];
                option.text = options[i];
                selectElement.add(option);
            }
        }

        function validerFormulaire() {

            // Effectuez les contrôles nécessaires
            var response = {
                status : true,
                message : '',
            }
            if ($('#berries_2').is(':checked') && !$('#inline-5').is(':checked')) {
                response.status=false
                response.message='Véhicule avec remorque doit être sélectionné pour assurer la remorque contre l\'incendie.'
            }
            if ($('#berries_2').is(':checked') && !$('#valeur_venale ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale'
            }
            if ($('#berries_4').is(':checked')  && !$('#valeur_venale ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale'
                }
            if ($('#berries_4').is(':checked') && !$('#inline-5').is(':checked')) {
                response.status=false
                response.message='véhicule avec remorque doit être sélectionné pour assurer la remorque contre l\'incendie.'
                }
            if ($('#inline-9').is(':checked') && !$('#capitale_assure option:selected').val()) {
                response.status=false
                response.message='veuiller choisir un capitale assure.' 
                }

            if ($('#inline-11').is(':checked') && !$('#option_pers_tr option:selected').val()) {
                response.status=false
                response.message='veuiller choisir une option de personne transporter'
                }

            if ($('#berries_1').is(':checked') && !$('#valeur_venale ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale'
                }
            if ($('#berries_2').is(':checked') && !$('#berries_1').is(':checked')) {
            response.status=false
            response.message='veuiller vous assurez en incendie'
            }

            if ($('#berries_4').is(':checked') && !$('#berries_3').is(':checked')) {
            response.status=false
            response.message='veuiller vous assurez a vol'
            }

            if ($('#berries_3').is(':checked') && !$('#valeur_venale ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale'
                }

            if ($('#inline-2').is(':checked') && !$('#valeur_neuf ').val()) {
                response.status=false
                response.message='veuiller donner une valeur neuf'
            }

            if ($('#inline-7').is(':checked') && !$('#valeur_neuf ').val()) {
                response.status=false
                response.message='veuiller donner une valeur neuf'
            }
           return response
        }

        function simulateurFormulaire() {
            // Validez le formulaire avant l'envoi des données
            if (validerFormulaire().status) {
                // Récupérez les données du formulaire
                const formData = $('#monFormulaire').serialize();

                // Effectuez une requête AJAX pour envoyer les données au serveur
                $.ajax({
                    url: '/traiter-donnees',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log('Réponse du serveur :', response);
                        afficherResultats(response);
                                    },
                    error: function (error) {
                        
                        console.error('Erreur lors de l\'envoi des données :', error);
                        // Utilisez SweetAlert pour afficher un message d'erreur         
                    }
                });
            }
            else{
                 // Utilisation de SweetAlert pour afficher le message d'erreur
                 swal({
                    title: "Attention",
                    text:validerFormulaire().message,
                    icon: "warning",
                    button: "OK",
                })
                .then(() => {
                    // Rediriger ou effectuer d'autres actions nécessaires
                    return false;
                });
                
            }
        }
        
        function calculerPrimeTtc(prime) {
            return (prime + (Taxe * prime) + Coup_Police); // Ajoutez 14% de la prime nette plus le coup de police
        }
            
        function afficherResultats1(data)
        {
            donnee = data;
            // Sélectionnez la div pour afficher les résultats
            var resultatsDiv = $('#resultats');

            // Effacez les résultats précédents
            resultatsDiv.empty();

            // Ajoutez les nouvelles données sous forme de boîtes de détails de produit
            $.each(donnee.donnee_produits, function (index, enregistrement) {
                
                // Créez une boîte de détails de produit
                var boiteDetails = $('<div>').addClass('prooduct-details-box col-md-6');

                // Créez la partie image de la boîte
                var imgDiv = $('<div>').addClass('media');
                // Vérifiez si le logo est présent dans l'enregistrement

                // Créez la partie texte de la boîte
                var textDiv = $('<div>').addClass('media-body ms-3');
                textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.nom_complet + '</a></h6></div>');
                textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.garantie + '</a></h6></div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime net</div>: ' +(enregistrement.prime).toFixed(2) + ' CFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Taxe</div>: ' + (Taxe * enregistrement.prime ).toFixed(2)+ ' CFA </div>');                
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Coup de police</div>: ' + Coup_Police + ' CFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Bonus commercial</div>: ' + Math.round(Bonus_commercial*100 )+ ' % </div>'); 
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime TTC</div>: ' + calculerPrimeTtc(enregistrement.prime).toFixed(2) + ' CFA</div>');
                textDiv.append('<div class="avaiabilty"></div><a class="btn btn-primary btn-xs" type="button" id="buttonSouscrir" onclick="souscrirFormulaire(' + index + ')">souscrire</a>');

                // Ajouter la partie texte à l'image
                imgDiv.append(textDiv);

                // Ajoutez la partie image et texte à la boîte
                boiteDetails.append(imgDiv);

                // Ajoutez la boîte à la div
                resultatsDiv.append(boiteDetails);
            });
        }
        function afficherResultats(data) {
            donnee = data;
            var resultatsDiv = $('#resultats');
            resultatsDiv.empty();

            if (donnee && (donnee.donnee_produits || donnee.donnee_produit)) {
                var produits = donnee.donnee_produits || donnee.donnee_produit;

                if (produits.length > 0) {
                    var categorieProduit = produits[0].cat_vehicule;

                    $.each(produits, function (index, enregistrement) {
                        var boiteDetails = $('<div>').addClass('prooduct-details-box col-md-6');
                        var imgDiv = $('<div>').addClass('media');
                        var textDiv = $('<div>').addClass('media-body ms-3');
                        
                        textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.nom_complet + '</a></h6></div>');
                        textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.garantie + '</a></h6></div>');
                        textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime net</div>: ' + (enregistrement.prime).toFixed(2) + ' CFA</div>');
                        textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Taxe</div>: ' + (Taxe * enregistrement.prime).toFixed(2) + ' CFA </div>');
                        textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Coup de police</div>: ' + Coup_Police + ' CFA</div>');
                        textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Bonus commercial</div>: ' + Math.round(Bonus_commercial * 100) + ' % </div>');
                        textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime TTC</div>: ' + calculerPrimeTtc(enregistrement.prime).toFixed(2) + ' CFA</div>');
                        textDiv.append('<div class="avaiabilty"></div><a class="btn btn-primary btn-xs" type="button" id="buttonSouscrir" onclick="souscrirFormulaire(' + index + ')">souscrire</a>');

                        imgDiv.append(textDiv);
                        boiteDetails.append(imgDiv);
                        resultatsDiv.append(boiteDetails);
                    });
                }
            }
        }

        function souscrirFormulaire(index) {
            // Récupérez l'enregistrement spécifique avec l'index
            var enregistrement = donnee.donnee_produits[index];
            // var enregistrements = donnee.donnee_produit[index];
            var energie = $('#energie').val();
            var valeur_venale = $('#valeur_venale').val();
            var valeur_neuf = $('#valeur_neuf').val();
            var capitale_assure = $('#capitale_assure').val();
            var nombre_place = $('#nombre_place').val();
            var option_pers_tr = $('#option_pers_tr').val();
            
            // Remplissez les champs du modal avec les informations du souscripteur
            $('#modalNomComplet').val(enregistrement.nom_complet);
            $('#modalGarantie').val(enregistrement.garantie);
            $('#modalPrimeNet').val((enregistrement.prime).toFixed(2));
            $('#modalTaxe').val(Math.round(Taxe * enregistrement.prime ));
            $('#modalBonus').val(Math.round(Bonus_commercial*100 ));
            $('#modalCoupP').val(Coup_Police);
            $('#modalPrime').val(calculerPrimeTtc(enregistrement.prime).toFixed(2) );

            // Ouvrez le modal
            $('#resultModal').modal('show');

            $('#catvehicule').val(enregistrement.cat_vehicule);
            $('#carrosseriee').val(enregistrement.type_carrosserie);
            $('#poid_carrosserie').val(enregistrements.poid_carrosserie);
            $('#energy').val(energie);
            $('#forcefiscale').val(enregistrement.force_fiscale);
            $('#valeurvenale').val(valeur_venale);
            $('#valeurneuf').val(valeur_neuf);
            $('#nbrplace').val(nombre_place);
            $('#modaloption').val(option_pers_tr);
            
            $('#capitaleassure').val(capitale_assure);

        
            // console.log('Énergie du véhicule :',energie);
            console.log('type du véhicule :',enregistrement.type_carrosserie);
            // console.log('poid du véhicule :',enregistrements.poid_carrosserie);
            // console.log('Valeur vénale du véhicule :',valeur_venale);

        }

        // Attachez l'événement de clic au bouton "Envoyer"
        $('#boutonSimuler').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            simulateurFormulaire();
        });
        $('#buttonSouscrir').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            souscrirFormulaire(index);
        
        });
        $('#buttonSoumettre').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            soumettre();
        
        });
    </script>

<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>