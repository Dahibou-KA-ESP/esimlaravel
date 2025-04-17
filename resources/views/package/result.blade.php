@extends('layouts.master')

@section('title', 'Resultat simulation')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/scss" href="{{asset('assets/css/layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

@endsection

@section('style')
    <style>
        .package-card {
            margin-bottom: 30px;
        }
        .package-logo {
            width: 100px;
            height: auto;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Resultat simulation</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item ">Simulateur</li>
    <li class="breadcrumb-item active">resultat</li>

@endsection

@section('content')
<div class="card">
    <div class="container mt-5">
        @foreach($responseData['packages'] as $package)
            <div class="row">
                <div class="col-12">
                    <h2>{{ $package['nom'] }}</h2>
                    <p style="font-size: larger;">{{ $package['description'] }}</p>
                </div>
            </div>

            @if(isset($package['validpack']) && $package['validpack'] === true)
                @if(isset($package['compagnies']) && is_array($package['compagnies']))
                <div class="card">
                    <div class="row">
                        @foreach($package['compagnies'] as $compagnie)
                            <div class="col-md-4 package-card">
                                <div class="card">
                                    <!-- Contenu des cartes pour les compagnies -->
                                    <div class="card-header">
                                        <img src="{{ $compagnie['logo_base64'] }}" alt="{{ $compagnie['abr'] }}" class="package-logo">
                                    </div>
                                    <div class="card-body">
                                        <!-- Détails des garanties et prix -->
                                        <h6>Garanties et prix</h6>
                                        <ul>
                                            @foreach($compagnie['garanties'] as $garantie)
                                                <li>{{ $garantie['nom_garantie'] }} - {{ $garantie['prix_garantie'] }} F CFA</li>
                                            @endforeach
                                        </ul>
                                        <hr class="mt-1 mb-1">
                                        <!-- Détails des coûts -->
                                        <h7><strong>Prime Nette:</strong> {{ $compagnie['prime_net'] }} F CFA</h7><br>
                                        <h7><strong>Taxe:</strong> {{ $compagnie['taxe'] }} F CFA</h7><br>
                                        <h7><strong>Coût de police:</strong> {{ $compagnie['coup_police'] }} F CFA</h7><br>
                                        <h7><strong>FGA:</strong> {{ $compagnie['fga'] }} F CFA</h7><br>
                                        <h7><strong>Durée:</strong> {{ $compagnie['duree'] }} mois</h7><br>
                                        <hr class="mt-1 mb-1">
                                        <p><strong>Prime TTC:</strong> {{ $compagnie['prime_ttc'] }} F CFA</p>
                                    </div>
                                    <div class="availability">
                                        <button class="btn btn-primary text-white" style="float:right;" type="button" data-bs-toggle="modal" data-bs-target="#recapModal{{ $loop->parent->index }}_{{ $loop->index }}">Inscrire</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal récapitulatif pour chaque compagnie -->
                                <div class="modal fade" id="recapModal{{ $loop->parent->index }}_{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="recapModalLabel{{ $loop->parent->index }}_{{ $loop->index }}" aria-hidden="true" data-parent-index="{{ $loop->parent->index }}" data-child-index="{{ $loop->index }}">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg" >
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="recapModalLabel{{ $loop->parent->index }}_{{ $loop->index }}">Récapitulatif - {{ $package['nom'] }} - {{ $compagnie['nom_complet'] }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                                        <form action="{{route('souscrirPack')}}"  method="post" class="form-space theme-form"> 
                                                            <div class="row">
                                                                <div class="checkout-details">
                                                                    <div class="order-box">
                                                                        <div class="title-box">
                                                                            <div class="checkbox-title">
                                                                            <h4>Garanties et détails primes</h4>
                                                                            <!-- <span>Détail(s) Prime(s)</span> -->
                                                                            </div>
                                                                        </div>
                                                                        <ul style="text-align: center;" class="qty" id="garantiesList">
                                                                            @foreach($compagnie['garanties'] as $garantie)
                                                                                <li>{{ $garantie['nom_garantie'] }} -- {{ $garantie['prix_garantie'] }} F CFA</li>
                                                                            @endforeach
                                                                        </ul>
                                                                        <ul class="sub-total total">
                                                                            <li>Prime nette<span class="count" id="">{{ $compagnie['prime_net'] }} F CFA</span></li>
                                                                            <li>Taxe <span class="count" id="">{{ $compagnie['taxe'] }} F CFA</span></li>
                                                                            <li>Coût de police <span class="count" id="">{{ $compagnie['coup_police'] }} F CFA</span></li>
                                                                            <li>FGA <span class="count" id="fga">{{ $compagnie['fga'] }} F CFA</span></li>
                                                                        </ul>
                                                                        <ul class="sub-total">
                                                                            <li>Total <span class="count" id="primeTTC">{{ $compagnie['prime_ttc'] }} F CFA</span></li>
                                                                        </ul>
                                                                        
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="row">
                                                                {{ csrf_field()}}
                                                                <h6>Renseignez information assuré</h6>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">Civilité</label>
                                                                    <select  name="civilite" class="form-select" >
                                                                        <option selected=""  value="M.">Monsieur</option>
                                                                        <option  value="Mme.">Madame</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">Prénom et Nom</label>
                                                                    <input class="form-control" name="name" type="text" required >
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">Profession</label>
                                                                    <input class="form-control" name="profession" type="text" >
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">Addresse</label>
                                                                    <input class="form-control" name="adrss" type="text" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">Ville</label>
                                                                    <input class="form-control" name="ville" type="text" placeholder="Ex:DAKAR" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-form-label">E-mail</label>
                                                                    <input class="form-control" name="email" type="email" placeholder="exemple@exemple.com" required>
                                                                </div>
                                                                <!-- <div class="col-md-4">
                                                                    <label class="col-form-label">Tél</label>
                                                                    <input class="form-control" name="phone" type="Number" placeholder="Ex:221771234567" required>
                                                                </div> -->

                                                                <hr class="mt-4 mb-4">
                                                                <h6 class="pb-3 mb-0">Renseignez Caractéristiques véhicule</h6>	

                                                                
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Marque</label>
                                                                    <select class="form-select" id="marqueSelect{{ $loop->parent->index }}_{{ $loop->index }}" required>
                                                                        <option value="" disabled selected>Choisissez une marque</option>
                                                                    </select>
                                                                    <input class="form-control" type="text" id="marqueInput{{ $loop->parent->index }}_{{ $loop->index }}" placeholder="Saisir une marque" style="display:none;">
                                                                    <button type="button" id="toggleMarque{{ $loop->parent->index }}_{{ $loop->index }}" class="btn btn-primary btn-sm mt-2">Saisir une nouvelle marque</button>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="form-label">Modèle</label>
                                                                    <select class="form-select" id="modeleSelect{{ $loop->parent->index }}_{{ $loop->index }}" required>
                                                                        <option value="" disabled selected>Choisissez un modèle</option>
                                                                    </select>
                                                                    <input class="form-control" type="text" id="modeleInput{{ $loop->parent->index }}_{{ $loop->index }}" placeholder="Saisir un modèle" style="display:none;">
                                                                    <button type="button" id="toggleModele{{ $loop->parent->index }}_{{ $loop->index }}" class="btn btn-primary btn-sm mt-2">Saisir un nouveau modèle</button>
                                                                </div>
                                                                
                                                                
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Immatriculation/chasis</label>
                                                                    <input class="form-control" name="vin" type="text" required>
                                                                </div>
                                                                <!-- <div class="col-md-4 ">
                                                                    <div class="date-picker">
                                                                        <label class="form-label">Date 1ere mise en circulation</label>
                                                                        <input class="datepicker-here form-control digits" id="date_first_circ" name="date_first_circ" value="{{ $compagnie['date_first_circ'] }}" type="text" data-language="fr" readonly required>
                                                                    </div>
                                                                </div> -->
                                                                <div class="col-md-4 ">
                                                                    <div class="date-picker">

                                                                        <label class="form-label">Date 1ere mise en circulation</label>
                                                                        <?php
                                                                            $date_first_circ = $compagnie['date_first_circ']; // Supposons que la date soit sous forme de chaîne au format 'd/m/Y'.
                                                                            
                                                                            // Essayer de créer un objet DateTime avec le format attendu
                                                                            try {
                                                                                $date = DateTime::createFromFormat('d/m/Y', $date_first_circ);
                                                                                // Vérifier si la conversion a réussi
                                                                                if ($date === false) {
                                                                                    throw new Exception("Invalid date format");
                                                                                }
                                                                                $date_formatted = $date->format('Y-m-d'); // Convertir en format 'Y-m-d' pour l'input date
                                                                            } catch (Exception $e) {
                                                                                $date_formatted = ''; // Valeur par défaut si la conversion échoue
                                                                            }
                                                                        ?>
                                                                        <input class="form-control" id="date_first_circ" name="date_first_circ" value="<?= htmlspecialchars($date_formatted); ?>" type="date" readonly>
                                                                    </div>
                                                                </div>  
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Energie</label>
                                                                    <input  class="form-control" name="energie" value="{{ $compagnie['energie'] }}" type="text" readonly >
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Force Fiscale</label>
                                                                    <input  class="form-control" name="force_fiscale" value="{{ $compagnie['force_fiscale'] }}" type="text" readonly >
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Nombre de place</label>
                                                                    <input  class="form-control" name="nombre_place" value="{{ $compagnie['nombre_place'] }}" type="text" readonly >
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Valeur neuve</label>
                                                                    <input  class="form-control" name="valeur_neuf" value="{{ $compagnie['valeur_neuf'] }}" type="number" readonly >
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label" >Valeur vénale</label>
                                                                    <input  class="form-control" name="valeur_venale" value="{{ $compagnie['valeur_venale'] }}" type="number" readonly >
                                                                </div> 
                                                                <hr class="mt-4 mb-4">
                                                                <h6 class="pb-3 mb-0">Validité du contrat</h6>

                                                                <!-- <div class="col-md-4  ">
                                                                    <div class="date-picker">
                                                                        <label class="form-label">Date d'effet</label>
                                                                        <input class="datepicker-here form-control digits" id="datepicker" name="date_effet"  type="text" data-language="fr" readonly required>
                                                                    </div>
                                                                </div> -->
                                                                <!-- <div class="col-md-4  ">
                                                                    <div class="date-picker">
                                                                        <label class="form-label">Date d'effet</label>
                                                                        <input class="form-control" id="date_effet" name="date_effet"  type="date"  required>
                                                                    </div>
                                                                </div> -->
                                                                <div class="col-md-4">
                                                                    <div class="date-picker">
                                                                        <label class="form-label">Date d'effet</label>
                                                                        <input class="form-control" id="date_effet" name="date_effet"  type="date"  required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label  class="form-label" >Durée(en mois)</label>
                                                                    <input  class="form-control" name="duree" value="{{ $compagnie['duree'] }}" type="text" readonly >
                                                                </div>
                                                                

                                                                    <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['id_compagnie'] }}" name="compagnie" type="text" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            
                                                                            @php
                                                                            $garantiesArray = array_map(function($garantie) {
                                                                                return [
                                                                                    'id_garantie' => $garantie['id_garantie'],
                                                                                    'nom_garantie' => $garantie['nom_garantie'],
                                                                                    'prix_garantie' => $garantie['prix_garantie']
                                                                                ];
                                                                            }, $compagnie['garanties']);
                                                                        @endphp
                                                                        <input id="modalGarantie" class="form-control" value="{{ json_encode($garantiesArray) }}" name="garantie" type="text" hidden readonly>
                                                                        </div>                                                  
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['prime_net'] }}" name="prime_net" type="number" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['taxe'] }}" name="taxe" type="number" hidden readonly >
                                                                        </div>
                                                                        
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['coup_police'] }}" name="coup_police" type="number" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['fga'] }}" name="fga" type="number" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['prime_ttc'] }}" name="prime_ttc"  type="number" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $compagnie['logo'] }}" name="logo" type="text" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                                <input  class="form-control" value="{{ $compagnie['type_carrosserie'] }}" name="type_carrosserie" type="text" hidden readonly >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input  class="form-control" name="contact" value="{{ $compagnie['contact'] }}" type="text" hidden readonly >
                                                                        </div>
                                                                        

                                                                        


                                                                    <div class="card-footer text-end">
                                                                        <button class="btn btn-primary" type="submit" >souscrire</button>
                                                                    </div>                                     
                                                            </div>                                         
                                                        </form>
                                                    </div>
                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- EndModal récapitulatif pour chaque compagnie -->
                        @endforeach
                    </div>
                </div>
                    
                @endif
            @else
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-danger">{{ $package['errormessage'] }}</h6>
                    </div>
                </div>
            @endif
            <hr>
        @endforeach
    </div>
</div>

@endsection

@section('script')

<!-- <script>
    var form = document.createElement("form");
form.method = "post";  // Set the form method to POST

// Add the CSRF token input
var csrfTokenInput = document.createElement("input");
csrfTokenInput.type = "hidden";
csrfTokenInput.name = "_token";
csrfTokenInput.value = "{{ csrf_token() }}";  // Add the CSRF token value

// Append the CSRF token input to the form
form.appendChild(csrfTokenInput);

// Append the form to the body or another container
document.body.appendChild(form);

// Optionally, submit the form
form.submit();
</script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>

<script>
    // Définir la date maximale à aujourd'hui
    const today = new Date().toISOString().split("T")[0];
    document.getElementById('date_first_circ').max = today;

    // Empêcher la saisie manuelle d'une date supérieure à la date du jour
    document.getElementById('date_first_circ').addEventListener('input', function() {
        const inputDate = this.value;
        if (inputDate > today) {
            this.value = ''; // Réinitialise la valeur si la date est supérieure à aujourd'hui
            alert('La date ne peut pas être supérieure à la date du jour.');
        }
    });

     // Définir la date maximale à aujourd'hui
     const today1 = new Date().toISOString().split("T")[0];
    document.getElementById('date_effet').min = today1;

    // Empêcher la saisie manuelle d'une date supérieure à la date du jour
    document.getElementById('date_effet').addEventListener('input', function() {
        const inputDate = this.value;
        if (inputDate < today1) {
            this.value = ''; // Réinitialise la valeur si la date est supérieure à aujourd'hui
            alert('La date ne peut pas être antérieur à la date du jour.');
        }
    });
</script>

<!-- <script>
        // Fonction pour charger et trier les marques et les modèles depuis le fichier JSON
        function loadMarquesEtModeles() {
            fetch('/marques.json')
                .then(response => response.json())
                .then(data => {
                    const marqueSelect = document.getElementById('marqueSelect');
                    const modeleSelect = document.getElementById('modeleSelect');

                    // Trier les marques par ordre alphabétique
                    data.sort((a, b) => a.marque.localeCompare(b.marque));

                    // Remplir le select des marques
                    data.forEach(marque => {
                        const option = document.createElement('option');
                        option.value = marque.marque;
                        option.textContent = marque.marque;
                        marqueSelect.appendChild(option);
                    });

                    // Trier les modèles par ordre alphabétique en fonction de la marque sélectionnée
                    marqueSelect.addEventListener('change', function() {
                        const selectedMarque = this.value;
                        const selectedMarqueData = data.find(marque => marque.marque === selectedMarque);

                        // Réinitialiser le select des modèles
                        modeleSelect.innerHTML = '<option value="" disabled selected>Choisissez un modèle</option>';

                        if (selectedMarqueData) {
                            // Trier les modèles par ordre alphabétique
                            const sortedModeles = selectedMarqueData.modeles.sort((a, b) => a.localeCompare(b));
                            sortedModeles.forEach(modele => {
                                const option = document.createElement('option');
                                option.value = modele;
                                option.textContent = modele;
                                modeleSelect.appendChild(option);
                            });
                        }
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des données JSON:', error));
        }

        // Charger les données lorsque la page est prête
        document.addEventListener('DOMContentLoaded', loadMarquesEtModeles);
</script> -->

<script>
    function loadMarquesEtModeles(parentIndex, childIndex) {
            fetch('/marques.json')
            .then(response => response.json())
            .then(data => {
                const marqueSelect = document.getElementById(`marqueSelect${parentIndex}_${childIndex}`);
                const modeleSelect = document.getElementById(`modeleSelect${parentIndex}_${childIndex}`);
                const marqueInput = document.getElementById(`marqueInput${parentIndex}_${childIndex}`);
                const modeleInput = document.getElementById(`modeleInput${parentIndex}_${childIndex}`);
                const toggleMarque = document.getElementById(`toggleMarque${parentIndex}_${childIndex}`);
                const toggleModele = document.getElementById(`toggleModele${parentIndex}_${childIndex}`);

                // Trier les marques par ordre alphabétique
                data.sort((a, b) => a.marque.localeCompare(b.marque));

                // Remplir le select des marques
                data.forEach(marque => {
                    const option = document.createElement('option');
                    option.value = marque.marque;
                    option.textContent = marque.marque;
                    marqueSelect.appendChild(option);
                });

                marqueSelect.addEventListener('change', function() {
                    const selectedMarque = this.value;
                    marqueInput.style.display = 'none';
                    marqueInput.removeAttribute('name');
                    marqueSelect.setAttribute('name', 'marque');
                    marqueSelect.setAttribute('required', 'required');

                    toggleMarque.textContent = 'Saisir une nouvelle marque';
                    
                    modeleSelect.innerHTML = '<option value="" disabled selected>Choisissez un modèle</option>';
                    modeleInput.style.display = 'none';
                    toggleModele.style.display = 'none';
                    modeleSelect.setAttribute('name', 'model');
                    modeleSelect.setAttribute('required', 'required');
                    modeleInput.removeAttribute('name');

                    if (selectedMarque) {
                        const selectedMarqueData = data.find(marque => marque.marque === selectedMarque);
                        if (selectedMarqueData) {
                            const sortedModeles = selectedMarqueData.modeles.sort((a, b) => a.localeCompare(b));
                            sortedModeles.forEach(modele => {
                                const option = document.createElement('option');
                                option.value = modele;
                                option.textContent = modele;
                                modeleSelect.appendChild(option);
                            });
                            toggleModele.style.display = 'block';
                        }
                    }
                });

                toggleMarque.addEventListener('click', function() {
                    const isMarqueInputVisible = marqueInput.style.display === 'block';

                    marqueSelect.style.display = isMarqueInputVisible ? 'block' : 'none';
                    marqueInput.style.display = isMarqueInputVisible ? 'none' : 'block';
                    
                    if (isMarqueInputVisible) {
                        marqueSelect.setAttribute('name', 'marque');
                        marqueSelect.setAttribute('required', 'required');
                        marqueInput.removeAttribute('name');
                        marqueInput.removeAttribute('required');
                    } else {
                        marqueInput.setAttribute('name', 'marque');
                        marqueInput.setAttribute('required', 'required');
                        marqueSelect.removeAttribute('name');
                        marqueSelect.removeAttribute('required');
                    }

                    this.textContent = isMarqueInputVisible ? 'Saisir une nouvelle marque' : 'Choisir une marque existante';
                });

                toggleModele.addEventListener('click', function() {
                    const isModeleInputVisible = modeleInput.style.display === 'block';

                    modeleSelect.style.display = isModeleInputVisible ? 'block' : 'none';
                    modeleInput.style.display = isModeleInputVisible ? 'none' : 'block';
                    
                    if (isModeleInputVisible) {
                        modeleSelect.setAttribute('name', 'model');
                        modeleSelect.setAttribute('required', 'required');
                        modeleInput.removeAttribute('name');
                        modeleInput.removeAttribute('required');
                    } else {
                        modeleInput.setAttribute('name', 'model');
                        modeleInput.setAttribute('required', 'required');
                        modeleSelect.removeAttribute('name');
                        modeleSelect.removeAttribute('required');
                    }

                    this.textContent = isModeleInputVisible ? 'Saisir un nouveau modèle' : 'Choisir un modèle existant';
                });
            })
            .catch(error => console.error('Erreur lors du chargement des données JSON:', error));
    }
    // Utiliser l'attribut data-* pour appeler la fonction avec les bons indices
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const parentIndex = modal.getAttribute('data-parent-index');
            const childIndex = modal.getAttribute('data-child-index');
            loadMarquesEtModeles(parentIndex, childIndex);
        });
    });
</script>
@endsection