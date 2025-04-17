
 <!-- latest jquery-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <!-- Bootstrap js-->
<script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- scrollbar js-->
<script src="{{asset('assets/js/scrollbar/simplebar.js')}}"></script>
<script src="{{asset('assets/js/scrollbar/custom.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script id="menu" src="{{asset('assets/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('assets/js/header-slick.js') }}"></script>
    

<!-- script function -->
<script>
        function changerOptions() {
            var carburantSelect = document.getElementById("energie");
            var modeleSelect = document.getElementById("fc");

            // Supprimer les options actuelles
            modeleSelect.innerHTML = "";

            // Ajouter de nouvelles options en fonction du carburant choisi
            if (carburantSelect.value === "essence") {
                var optionsEssence = ["2", "3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"];
                ajouterOptions(optionsEssence, modeleSelect);
            } else if (carburantSelect.value === "diesel") {
                var optionsDiesel = ["2", "3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"];
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

    //     function toggleSecondSelect() {
    //     var firstSelectValue = $('#cat_vehicule').val();
    //     if (firstSelectValue === 'cat2') {
    //         $('#carrosserieLabel, #carrosserie').show();
    //     } else {
    //         $('#carrosserieLabel, #carrosserie').hide();
    //     }
    // }

    // Initial toggle based on the default value of the first select
    //toggleSecondSelect();

    // // Event listener to toggle the second select when the first select changes
    // $('#cat_vehicule').on('change', toggleSecondSelect);

    function toggleSecondSelect1() {
         var firstSelectValue = $('#carrosserie').val();
         if (firstSelectValue === 'autre_car') {
             $('#poid_carrosserieLabel, #poid_carrosserie').show();
         } else {
             $('#poid_carrosserieLabel, #poid_carrosserie').hide();
         }
     }

    // Initial toggle based on the default value of the first select
    toggleSecondSelect1();

    // Event listener to toggle the second select when the first select changes
     $('#carrosserie').on('change', toggleSecondSelect1);

    

</script>
<!-- end-script function -->


@yield('script')

@if(Route::current()->getName() != 'popover') 
	<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
@endif

<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('assets/js/script.js')}}"></script>
<script src="{{asset('assets/js/theme-customizer/')}}"></script>


{{-- @if(Route::current()->getName() == 'index') 
	<script src="{{asset('assets/js/layout-change.js')}}"></script>
@endif --}}

@if(Route::currentRouteName() == 'index')
<script>
	new WOW().init();
</script>
@endif
<script src="{{asset('assets/js/script_indicatif.js')}}"></script>
<script src="{{asset('assets/js/cdn_indicatif.js')}}"></script>
<script>
    const form = {
        init: function() {
            form.input().addEventListener("focus", form.reset);
            form.input().addEventListener("input", form.reset);

            // Ajouter un écouteur d'événement pour le bouton de validation
            document.querySelector("#validate-button").addEventListener("click", form.triggerSubmit);
        },
        input: function() {
            return document.querySelector("#phone");
        },
        phoneInput: intlTelInput(document.querySelector("#phone"), {
            initialCountry: "sn",
            preferredCountries: ["fr", "es", "be", "gb", "de"],
            separateDialCode: true,
        }),
        reset: function() {
            form.input().classList.remove("error");
            form.input().classList.remove("success");
        },
        validation: function() {
             // Vérifier si le champ est vide
             if (form.input().value.trim() === "") {
                // alert("Veuillez entrer un numéro de téléphone.");
                form.input().classList.add("error");
                return true;
            }
            if (!form.phoneInput.isValidNumber()) {
                alert("Numéro de téléphone invalide");
                form.input().classList.add("error");
                return false;
            }
            form.input().classList.add("success");
            console.log("Numéro valide : " + form.phoneInput.getNumber(intlTelInputUtils.numberFormat.E164));
            return true;
        },
        triggerSubmit: function() {
            if (form.validation()) {
                // Mettre à jour la valeur du champ avec le numéro complet (indicatif inclus)
                form.input().value = form.phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
                
                // Soumettre le formulaire
                document.querySelector('#hidden-submit').click();
            }
        }
    };

    // Initialisation lors du chargement de la page
    document.addEventListener("DOMContentLoaded", form.init);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialisation pour chaque champ de téléphone
        document.querySelectorAll(".phone-input").forEach(function (inputElement, index) {
            const phoneInput = intlTelInput(inputElement, {
                initialCountry: "sn",
                preferredCountries: ["fr", "es", "be", "gb", "de"],
                separateDialCode: true,
            });

            // Réinitialiser les styles d'erreur/succès
            inputElement.addEventListener("focus", resetInput);
            inputElement.addEventListener("input", resetInput);

            // Ajouter un écouteur pour le bouton de validation correspondant
            const validateButton = document.querySelector(`.validate-button[data-index="${index}"]`);
            if (validateButton) {
                validateButton.addEventListener("click", function () {
                    if (validatePhoneNumber(phoneInput, inputElement)) {
                        // Mettre à jour le champ avec le numéro complet (indicatif inclus)
                        inputElement.value = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);

                        // Soumettre le formulaire correspondant
                        document.querySelector(`#hidden-submit-${index}`).click();
                    }
                });
            }
        });

        // Fonction pour réinitialiser les styles
        function resetInput(event) {
            event.target.classList.remove("error");
            event.target.classList.remove("success");
        }

        // Fonction de validation du numéro de téléphone
        function validatePhoneNumber(phoneInput, inputElement) {
            if (inputElement.value.trim() === "") {
                // alert("Veuillez entrer un numéro de téléphone.");
                // inputElement.classList.add("error");
                return true;
            }
            if (!phoneInput.isValidNumber()) {
                alert("Numéro de téléphone invalide");
                inputElement.classList.add("error");
                return false;
            }
            inputElement.classList.add("success");
            console.log("Numéro valide : " + phoneInput.getNumber(intlTelInputUtils.numberFormat.E164));
            return true;
        }
    });
</script>
