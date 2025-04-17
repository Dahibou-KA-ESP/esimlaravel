@extends('layouts.master')

@section('title', 'Resultat simulation')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/scss" href="{{asset('assets/css/layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/cdn_css_intel.css')}}">
    <link rel="stylesheet" href="styles.css" />
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
    <h3>Resultats simulations voyage</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item ">Simulateur</li>
    <li class="breadcrumb-item active">resultat voyage</li>

@endsection

@section('content')
<div class="card">
    <div class="container mt-5">
        <div class="card">
            <div class="row">
            @foreach($responseData['voyage'] as $package)
                    <div class="col-md-4">
                        <div class="card">
                            <!-- Contenu des cartes pour les compagnies -->
                           
                            <div class="card-body text-center ">
                                <img src="{{ $package['logo_base64'] }}" alt="{{ $package['abr'] }}" class="package-logo"><br>
                                <br><h4><strong>{{ $package['nom'] }}</strong> </h4>
                                <hr class="mt-1 mb-1">
                                <div class="text-center">
                                <h5>Récapitulatifs</h5>
                                </div>
                                <hr class="mt-1 mb-1"><br>
                                <!-- Détails des coûts -->
                                 <div class="text-start">
                                 <h6>Date depart: {{ $package['date_depart'] }}</h6>
                                <h6>Date retour: {{ $package['date_retour'] }}</h6>
                                <h6>Date de naissance: {{ $package['date_naissance'] }}</h6>
                                <h6>Pays destination: {{ $package['pays_destination'] }}</h6>
                                <!-- <h6>Code pays: {{ $package['code_pays'] }}</h6> -->
                                <h6>Age: {{ $package['age'] }} An(s)</h6>
                                <h6>Durée: {{ $package['duree_voyage'] }} jours</h6><br>
                                <hr class="mt-1 mb-1">
                                 </div>
                                 <h3><strong>Prime TTC:</strong> {{ $package['prime_ttc'] }} F CFA</h3>

                            </div>
                            <div class="availability">
                                <button class="btn btn-primary text-white" style="float:right;" type="button" data-bs-toggle="modal" data-bs-target="#modalPackage{{ $loop->index }}">Inscrire</button>
                            </div>
                        </div>
                    </div>
                        <!-- Modal récapitulatif pour chaque compagnie -->
                            <div class="modal fade" id="modalPackage{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog  modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Renseignez information souscripteur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                                    <form action="{{route('souscrirVoyage')}}"  method="post" class="form-space theme-form"> 
                                                        <div class="row">
                                                            {{ csrf_field()}}
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
                                                           
                                                            
                                                            
                                                                
                                                            <hr class="mt-4">
                                                          
                                                        </div>
                                                        <div class="modal-header">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                <h6 style="color:red;">Si le souscripteur n'est pas l'assuré renseignez les informations de l'assuré sinon renseignez juste le <strong>N° de votre passport</strong></h6>

                                                                </div>
                                                                <div class="col-md-12">
                                                                <h5 class="modal-title" id="">Renseignez informations assuré</h5>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            {{ csrf_field()}}
                                                            <!-- <div class="col-md-4">
                                                                <label class="col-form-label">Civilité</label>
                                                                <select  name="civilite_beneficiaire" class="form-select" >
                                                                    <option selected=""  value="M.">Monsieur</option>
                                                                    <option  value="Mme.">Madame</option>
                                                                </select>
                                                            </div> -->
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Prénom et Nom</label>
                                                                <input class="form-control" name="name_beneficiaire" type="text"  >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Profession</label>
                                                                <input class="form-control" name="profession_beneficiaire" type="text" >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Addresse</label>
                                                                <input class="form-control" name="adrss_beneficiaire" type="text" >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Ville</label>
                                                                <input class="form-control" name="ville_beneficiaire" type="text" placeholder="Ex:DAKAR" >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">E-mail</label>
                                                                <input class="form-control" name="email_beneficiaire" type="email" placeholder="exemple@exemple.com" >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Tél</label>
                                                                <input id="phone1{{ $loop->index }}" class="form-control phone-input" name="contact_beneficiaire" type="tel">
                                                                </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="col-form-label">Numero passport</label>
                                                                <input class="form-control" name="passport" type="text" placeholder="Ex:A2559" required>
                                                            </div>

                                                            <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['id_compagnie'] }}" name="compagnie_id" type="text" hidden readonly >
                                                                        <input class="form-control" name="contact" type="tel" value="{{ $package['contact'] }}" hidden     readonly>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['date_depart'] }}" name="date_depart" type="text" hidden readonly >
                                                                    </div>
                                                                    
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['date_retour'] }}" name="date_retour" type="text" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['date_naissance'] }}" name="date_naissance" type="text" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['pays_destination'] }}" name="pays_destination"  type="text" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" value="{{ $package['logo'] }}" name="logo" type="text" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                            <input  class="form-control" value="{{ $package['code_pays'] }}" name="code_pays" type="text"hidden  readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" name="age" value="{{ $package['age'] }}" type="number"  hidden  readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" name="prime_ttc" value="{{ $package['prime_ttc'] }}" type="number" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" name="duree" value="{{ $package['duree_voyage'] }}" type="number" hidden readonly >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input  class="form-control" name="id_produit_voyage" value="{{ $package['id_produit_voyage'] }}" type="number" hidden readonly >
                                                                    </div>
                                                                
                                                            <hr class="mt-4 mb-4">
                                                            
                                                                    
                                                            <div class="card-footer text-end">
                                                            <button class="btn btn-primary validate-button" data-index="{{ $loop->index }}" type="button">Souscrire</button>
                                                            <button type="submit" style="display:none;" id="hidden-submit-{{ $loop->index }}">Submit</button>
                                                            <!-- <button class="btn btn-primary" type="button" id="validate-button">souscrire</button>
                                                            <button type="submit" style="display:none;" id="hidden-submit">Submit</button> -->
                                                                    <!-- <button class="btn btn-primary" type="submit" >souscrire</button> -->
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


@endsection