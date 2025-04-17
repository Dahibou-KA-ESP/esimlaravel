<script>
        var TaxeP=0.14;
        var coup_policeP=3000;

        function calculerPrimeTtc(prime) {
            return (prime  + coup_policeP + (TaxeP *( prime + coup_policeP)) ); // Ajoutez 14% de la prime nette plus le coup de police
        }
        function changerOptions1() {
            var carburantSelect = document.getElementById("energie");
            var modeleSelect = document.getElementById("force_fiscale");

            // Supprimer les options actuelles
            modeleSelect.innerHTML = "";

            // Ajouter de nouvelles options en fonction du carburant choisi
            if (carburantSelect.value === "essence") {
                var optionsEssence = ["2", "3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"];
                ajouterOptions(optionsEssence, modeleSelect);
            } else if (carburantSelect.value === "diesel") {
                var optionsDiesel = ["2", "3","4","5","6","7","8","9","10","11","12","13","14","15","16","17"];
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
            if ( $('#valeur_neuf ').val() < $('#valeur_venale ').val()) {
                response.status=false
                response.message='La valeur neuve doit être supérieure ou égale à la valeur venale.'
            }
            return response
        }
        function validerPack() {

            // Effectuez les contrôles nécessaires
            var response = {
                status : true,
                message : '',
            }
            
            if (!$('#valeur_venale ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale '
            }
            if (!$('#valeur_neuf').val()) {
                response.status=false
                response.message='veuiller donner une valeur neuve '
            }
            if (!$('#valeur_venale').val() && !$('#valeur_neuf ').val()) {
                response.status=false
                response.message='veuiller donner une valeur venale et une valeur neuve '
            }
            if ( $('#valeur_neuf ').val() < $('#valeur_venale ').val()) {
                response.status=false
                response.message='La valeur neuve doit être supérieure ou égale à la valeur venale.'
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
                    // Vérifiez la catégorie du formulaire
                    if (response.donnee_produits && response.donnee_produits.length > 0) {
                            // Accédez à la catégorie du premier produit dans donnee_produits
                            var categorieProduit = response.donnee_produits[0].cat_vehicule;

                            // Utilisez la catégorie pour afficher les résultats appropriés
                            if (categorieProduit === 'cat1') {
                                afficherResultats1(response);
                            } else if (categorieProduit === 'cat2') {
                                afficherResultats2(response);
                            }
                        } 
                        else if (response.donnee_produit && response.donnee_produit.length > 0) {
                            // Accédez à la catégorie du premier produit dans donnee_produit
                            var categorieProduit = response.donnee_produit[0].cat_vehicule;

                            // Utilisez la catégorie pour afficher les résultats appropriés
                            if (categorieProduit === 'cat1') {
                                afficherResultats1(response);
                            } else if (categorieProduit === 'cat2') {
                                afficherResultats2(response);
                            }
                        }
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
        
        function simulateurPack() {
            // Validez le formulaire avant l'envoi des données
            if (validerPack().status) {
                // Récupérez les données du formulaire
                const formData = $('#monPack').serialize();
                const apiKey = 'qwerty';
                // Effectuez une requête AJAX pour envoyer les données au serveur
                $.ajax({
                    url: 'http://idylsoft_comparateur.test/api/traiter-pack',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-Api-Key': apiKey // Ajoutez votre clé API en tant qu'en-tête 
                    },
                    success: function (response) {
                        console.log('Réponse du serveur :',response);
                        afficherResultats3(response);
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
                    text:validerPack().message,
                    icon: "warning",
                    button: "OK",
                })
                .then(() => {
                    // Rediriger ou effectuer d'autres actions nécessaires
                    return false;
                });
                
            }
        }
        
        function afficherResultats1(data){
            
            donnee = data;
            // Sélectionnez la div pour afficher les résultats
            var resultatsDiv = $('#resultats');
          
                // console.log(Duree_name);
            // Effacez les résultats précédents
            resultatsDiv.empty();

            // Ajoutez les nouvelles données sous forme de boîtes de détails de produit
            $.each(donnee.donnee_produits, function (index, enregistrement) {
                
               
                // Créez une boîte de détails de produit
                var boiteDetails = $('<div>').addClass('prooduct-details-box col-md-3');

                // Créez la partie image de la boîte
                var imgDiv = $('<div>').addClass('media');

                // Créez la partie texte de la boîte
                var textDiv = $('<div>').addClass('media-body ms-3');
                textDiv.append('<div class="media"><img class="align-self-center img-fluid img-60" src="' + '/storage/image/logo/' + enregistrement.logo + '"></div>');
                textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.nom_complet + '</a></h6></div>');
                // textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Garantie(s)</div>: ' + enregistrement.garantie + ' </div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime net</div>: ' + enregistrement.prime + ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Taxe</div>: ' +  enregistrement.Taxe + ' FCFA </div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Coût de police</div>: ' +enregistrement.coup_police + ' FCFA</div>');
                // Ajoutez la valeur de Fga
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Fga</div>: ' + enregistrement.fga + ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime TTC</div>: ' +enregistrement.prime_TTC+ ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Durée</div>: ' + enregistrement.Duree+ ' Mois </div>');
                textDiv.append('<div class="avaiabilty"></div><a class="btn btn-primary btn-xs" type="button" id="buttonSouscrir" onclick="souscrirFormulaire(' + index + ')">Voir plus</a>');

                // Ajouter la partie texte à l'image
                imgDiv.append(textDiv);

                // Ajoutez la partie image et texte à la boîte
                boiteDetails.append(imgDiv);

                // Ajoutez la boîte à la div
                resultatsDiv.append(boiteDetails);
            });
        }
        function afficherResultats2(data){
            
            donnee = data;
            // Sélectionnez la div pour afficher les résultats
            var resultatsDiv = $('#resultats');
            var Duree = $('#duree').val();
            var Duree_name = $('#duree option:selected').text();
            // Effacez les résultats précédents
            resultatsDiv.empty();

            // Ajoutez les nouvelles données sous forme de boîtes de détails de produit
            $.each(donnee.donnee_produits, function (index, enregistrement) {
                // Calcul de la FGA à partir de la prime RC
                var Fga = enregistrement.fga;
                var Fga_true = Fga*Duree;
                var prime =enregistrement.prime;
                var prime_true=prime*Duree;
                // Créez une boîte de détails de produit
                var boiteDetails = $('<div>').addClass('prooduct-details-box col-md-6');

                // Créez la partie image de la boîte
                var imgDiv = $('<div>').addClass('media');

                // Créez la partie texte de la boîte
                var textDiv = $('<div>').addClass('media-body ms-3');
                textDiv.append('<div class="media"><img class="align-self-center img-fluid img-60" src="' + '/storage/image/logo/' + enregistrement.logo + '"></div>');
                textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.nom_complet + '</a></h6></div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Garantie(s)</div>: ' + enregistrement.garantie + ' </div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime net</div>: ' + Math.round (prime_true) + ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Taxe</div>: ' +  Math.round (Taxe * (prime_true + Coup_Police)) + ' FCFA </div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Coup de police</div>: ' + Coup_Police + ' FCFA</div>');
                // Ajoutez la valeur de Fga
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Fga</div>: ' + Math.round(Fga_true) + ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Prime TTC</div>: ' +Math.round((calculerPrimeTtc(prime_true ))+Fga_true)  + ' FCFA</div>');
                textDiv.append('<div class="price d-flex"><div class="text-muted me-2">Durée</div>: ' + Duree_name + ' </div>');
                textDiv.append('<div class="avaiabilty"></div><a class="btn btn-primary btn-xs" type="button" id="buttonSouscrir" onclick="souscrirFormulaire(' + index + ')">souscrire</a>');


                // Ajouter la partie texte à l'image
                imgDiv.append(textDiv);

                // Ajoutez la partie image et texte à la boîte
                boiteDetails.append(imgDiv);

                // Ajoutez la boîte à la div
                resultatsDiv.append(boiteDetails);
            });
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
                    // Vérifiez la catégorie du formulaire
                    if (response.donnee_produits && response.donnee_produits.length > 0) {
                            // Accédez à la catégorie du premier produit dans donnee_produits
                            var categorieProduit = response.donnee_produits[0].cat_vehicule;

                            // Utilisez la catégorie pour afficher les résultats appropriés
                            if (categorieProduit === 'cat1') {
                                afficherResultats1(response);
                            } else if (categorieProduit === 'cat2') {
                                afficherResultats2(response);
                            }
                        } 
                        else if (response.donnee_produit && response.donnee_produit.length > 0) {
                            // Accédez à la catégorie du premier produit dans donnee_produit
                            var categorieProduit = response.donnee_produit[0].cat_vehicule;

                            // Utilisez la catégorie pour afficher les résultats appropriés
                            if (categorieProduit === 'cat1') {
                                afficherResultats1(response);
                            } else if (categorieProduit === 'cat2') {
                                afficherResultats2(response);
                            }
                        }
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

        function afficherResultats3(data) {
            donnee = data;
            var Duree = $('#duree').val();
            var Duree_name = $('#duree option:selected').text();
           
            // Parcourez les ID des packages
            for (var i = 0; i < packagesIds.length; i++) {
                // Obtenez l'ID du package en cours
                var packageId = packagesIds[i];

                // Sélectionnez la div spécifique au package
                var resultatsDiv = $('#resultatsP-' + packageId);

                // Effacez les résultats précédents pour ce package
                resultatsDiv.empty();

                // Filtrer les données pour le package en cours
                var produitsDetails = donnee.donnee_produits.find(function (produit) {
                    return Object.keys(produit)[0] == packageId;
                })[packageId];

                // Parcourez les produits et affichez les détails
                $.each(produitsDetails, function (indexP, enregistrement) {
                    

                    // Créez une boîte de détails de produit
                    var boiteDetails = $('<div>').addClass('prooduct-details-box col-md-');

                    // Créez la partie image de la boîte
                    var imgDiv = $('<div>').addClass('media');

                    // Créez la partie texte de la boîte
                    var textDiv = $('<div>').addClass('media-body ms-3');
                    textDiv.append('<div class=""><img class="align-self-center img-fluid img-60" src="' + '/storage/image/logo/' + enregistrement.logo + '"></div>');
                    textDiv.append('<div class="product-name"><h6><a href="#">' + enregistrement.nom_complet + '</a></h6></div>');
                    // textDiv.append('<div class="avaiabilty"></div><a class="btn btn-primary btn-xs" type="button" id="buttonSouscrirP" onclick="souscrirPackage(' + packageId + ',' + indexP + ')">Souscrire</a>');

                    // Ajoutez la partie texte à l'image
                    imgDiv.append(textDiv);

                    // Ajoutez la partie image et texte à la boîte
                    boiteDetails.append(imgDiv);

                    // Ajoutez la boîte à la div spécifique au package
                    resultatsDiv.append(boiteDetails);
                });
            }
        }
        
        function souscrirFormulaire(index) {
            // Récupérez l'enregistrement spécifique avec l'index
            var enregistrement = donnee.donnee_produits[index];

            

            $('#modalNomComplet').val(enregistrement.nom_complet);
            $('#modalLogo').val(enregistrement.logo);
            $('#modalPrimeNet').val(enregistrement.prime);
            $('#modalTaxe').val(enregistrement.Taxe);
            $('#modalCoupP').val(enregistrement.coup_police);
            $('#modalFga').val(enregistrement.fga);
            $('#modalPrime').val(enregistrement.prime_TTC);

            // console.log(garanties.nom_garantie)
            // Ouvrez le modal
            $('#resultModal').modal('show');

            $('#catvehicule').val(enregistrement.cat_vehicule); 
            $('#carrosseriee').val(enregistrement.type_carrosserie);
            // $('#poid_carrosserie').val(enregistrements.poid_carrosserie);
            $('#energy').val(enregistrement.energie);
            $('#duree_m').val(enregistrement.Duree);
            $('#forcefiscale').val(enregistrement.force_fiscale);
            $('#valeurvenale').val(enregistrement.valeur_venale);
            $('#valeurneuf').val(enregistrement.valeur_neuf);
            $('#nbrplace').val(enregistrement.nombre_place);
            $('#modalID').val(enregistrement.id_produit);
            $('#modaloption').val(enregistrement.option_pers_tr);
            
            $('#capitaleassure').val(enregistrement.capitale_assure);

                        // Sélectionner les éléments HTML nécessaires
            var subTotalItems = document.querySelectorAll('.sub-total li');
            var totalElement = document.querySelector('.total li .count');

            // Initialiser les variables pour stocker les valeurs
            var coupDePolice = enregistrement.coup_police;
            var fga = enregistrement.fga;
            var taxe = enregistrement.Taxe;
            var primeNette = enregistrement.prime;
            var primeTTC = enregistrement.prime_TTC;

                    // Mettre à jour les valeurs des éléments HTML avec les données récupérées
            document.getElementById('coupDePolice').textContent =  + coupDePolice +' '+'FCFA' ;
            document.getElementById('fga').textContent =  + fga+' ' + 'FCFA';
            document.getElementById('taxe').textContent =  + taxe+' ' + 'FCFA';
            document.getElementById('primeNette').textContent =  + primeNette+' ' + 'FCFA';
            document.getElementById('primeTTC').textContent =  + primeTTC+' ' + 'FCFA';

            // Chaîne JSON des garanties
            var garantiesJSON = enregistrement.garantie;

            // Supprimer les crochets supplémentaires et les virgules entre les objets
            var garantiesJsonString = '[' + garantiesJSON.replace(/\]\[/g, ',') + ']';

            // Convertir la chaîne JSON en tableau JavaScript
            var garanties = JSON.parse(garantiesJsonString);

            // Sélectionner l'élément HTML où vous souhaitez afficher les noms de garantie
            var modalGarantieElement = $('#modalGarantie');
            var modalPGarantieElement = $('#modalPG');

            // Chaîne pour stocker tous les noms de garantie
            var nomsGaranties = '';
             // Chaîne pour stocker tous les noms de garantie
             var prixGaranties = '';
            // Sélectionner l'élément ul pour afficher les garanties
            var garantiesList = document.getElementById('garantiesList');

                        // Vider la liste des garanties avant d'ajouter de nouvelles données
            garantiesList.innerHTML = '';
            // Parcourir le tableau de garanties pour les afficher
            garanties[0].forEach(function(garantie) {
                // Créer un nouvel élément li pour chaque garantie
                var garantieItem = document.createElement('li');
                
                // Construire le texte à afficher avec le nom de la garantie et son prix
                var text = garantie.nom_garantie + '  <span>' + garantie.prix_garantie +' '+'FCFA' +'</span>';
                // Définir le texte de l'élément li
                garantieItem.innerHTML = text;

                // Ajouter l'élément li à la liste des garanties
                garantiesList.appendChild(garantieItem);

                nomsGaranties += garantie.nom_garantie + ', ';

                prixGaranties += garantie.prix_garantie + ', ';
                
                
            });
                // Supprimer la virgule et l'espace en trop à la fin de la chaîne
                nomsGaranties = nomsGaranties.slice(0, -2);
                prixGaranties = prixGaranties.slice(0, -2);


                // Définir la valeur de l'élément HTML avec la liste des noms de garantie
                modalGarantieElement.val(nomsGaranties);

                // Définir la valeur de l'élément HTML avec la liste des prix de garantie
                modalPGarantieElement.val(prixGaranties);


        }

        function souscrirPackage(packageId,indexP) {
            // Ouvrez le modal
            $('#resultModalP').modal('show');
            // Sélectionnez l'enregistrement spécifique pour le package et l'index donnés
            var enregistrement = donnee.donnee_produits.find(function(produit) {
                return Object.keys(produit)[0] == packageId;
            })[packageId][indexP];
            
             // var enregistrements = donnee.donnee_produit[index];
             var energie = $('#energieP').val();
            var valeur_venale = $('#valeur_venale').val();
            var valeur_neuf = $('#valeur_neuf').val();
            var capitale_assure = $('#capitale_assure').val();
            var nombre_place = $('#nombre_place').val(); 
            var option_pers_tr = $('#option_pers_tr').val();
            var duree = $('#duree option:selected').text();
            var Duree = $('#duree').val();
            var Fga=enregistrement.fga*Duree;
            var prime_true =enregistrement.prime*Duree+Fga

            // Remplissez les champs du modal avec les informations du souscripteur
            $('#modalNomCompleta').val(enregistrement.nom_complet);
            $('#modalGarantie').val(enregistrement.garantie);
            $('#modalLogo').val(enregistrement.logo);
            $('#modalPrimeNet').val(Math.round (prime_true));
            $('#modalTaxe').val(Math.round (TaxeP * (prime_true + coup_policeP)));
            $('#modalBonus').val(Math.round(Bonus_commercial*100 ));
            $('#modalCoupP').val(coup_policeP);
            $('#modalFga').val( Math.round(Fga));
            $('#modalPrime').val(Math.round((calculerPrimeTtc(prime_true ))+Fga));

            // Ouvrez le modal
            $('#resultModal').modal('show');

            $('#catvehicule').val(enregistrement.cat_vehicule);
            $('#carrosseriee').val(enregistrement.type_carrosserie);
            // $('#poid_carrosserie').val(enregistrements.poid_carrosserie);
            $('#energy').val(energie);
            $('#duree_m').val(duree+' '+'mois');
            $('#forcefiscale').val(enregistrement.force_fiscale);
            $('#valeurvenale').val(valeur_venale);
            $('#valeurneuf').val(valeur_neuf);
            $('#nbrplace').val(nombre_place);
            $('#modaloption').val(option_pers_tr);
            
            $('#capitaleassure').val(capitale_assure);
             // console.log('Énergie du véhicule :',energie);
            // console.log('Nom assurance :',enregistrement.nom_complet);
            // console.log('poid du véhicule :',enregistrements.poid_carrosserie);
            // console.log('Valeur vénale du véhicule :',valeur_venale);


        }

        // Attachez l'événement de clic au bouton "Envoyer"
        $('#boutonSimuler').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            simulateurFormulaire();
        });

        // Attachez l'événement de clic au bouton "Envoyer"
        $('#boutonSimulerP').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            simulateurPack();
        });
        $('#buttonSouscrir').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            souscrirFormulaire(index);
        
        });
        $('#buttonSouscrirP').on('click', function () {
            // Appel à la fonction simulateurFormulaire pour gérer l'envoi des données
            souscrirFormulaire(indexP);
        
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