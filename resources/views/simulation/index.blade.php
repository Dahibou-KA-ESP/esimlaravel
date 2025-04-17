@extends('layouts.master')

@section('title', 'Edit Profile')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/scss" href="{{asset('assets/css/layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Simulateur</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Simulateur</li>
@endsection

@section('content')
    <!-- affichage simulateur  -->
        <div class="container-fluid">
            <div class="edit-profile">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="monFormulaire" action="{{route('simulate')}}" method="post" class="form-space theme-form row">
                                                {{ csrf_field()}}
                                            <h6 class="pb-3 mb-0">Caracteristiquess vehicule</h6>
                                                <div class="col-md-3">
                                                        <label for="cat_vehicule" class="form-label" >Catégorie Vehicule</label>
                                                        <select id="cat_vehicule" name="cat_vehicule" class="form-select" >
                                                            <option value="cat1">Vehicule particulier</option>
                                                            <!-- <option value="cat2">Vehicule de commerce</option> -->
                                                        </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="energie" class="form-label" >Energie</label>
                                                    <select id="energie" name="energie" class="form-select" onchange="changerOptions1()" >
                                                        <option selected=""  value="essence">Essence</option>
                                                        <option  value="diesel">Diesel</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="force_fiscale" class="form-label" >Force Fiscale</label>
                                                    <select id="force_fiscale" name="force_fiscale" class="form-select" >
                                                        <option selected=""  value="2">2</option>
                                                        <option   value="2">2</option>
                                                        <option   value="3">3</option>
                                                        <option   value="4">4</option>
                                                        <option   value="5">5</option>
                                                        <option   value="6">6</option>
                                                        <option   value="7">7</option>
                                                        <option   value="8">8</option>
                                                        <option   value="9">9</option>
                                                        <option   value="10">10</option>
                                                        <option   value="11">11</option>
                                                        <option   value="12">12</option>
                                                        <option   value="13">13</option>
                                                        <option   value="14">14</option>
                                                        <option   value="15">15</option>
                                                        <option   value="16">16</option>
                                                        <option   value="17">17</option>
                                                        <option   value="18">18</option>
                                                        <option   value="19">19</option>
                                                        <option   value="20">20</option>
                                                        <option   value="21">21</option>
                                                        <option   value="23">23</option>
                                                        <option   value="24">24 et plus</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nombre_place" class="form-label" >Nombre de place</label>
                                                    <select id="nombre_place" name="nombre_place" class="form-select" >
                                                        <option selected=""  value="5">5</option>
                                                        <option  value="7">7</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="duree" class="form-label" >Durée</label>
                                                        <select id="duree" name="duree" class="form-select">
                                                            @foreach ($bareme as $data)
                                                                <option value="{{ $data->nom }}" {{ ($data->taux === 1) ? 'selected' : '' }}>{{ $data->nom }} mois</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label  class="form-label" >Valeur neuve</label>
                                                    <input id="valeur_neuf" class="form-control" name="valeur_neuf" type="number" required >
                                                </div>
                                                <div class="col-md-3">
                                                    <label  class="form-label" >Valeur vénale</label>
                                                    <input id="valeur_venale" class="form-control" name="valeur_venale" type="number" required >
                                                </div>
                                                <div class="col-md-3">
                                                        <label for="carrosserie" id="carrosserieLabel" class="form-label" >Type carrosserie</label>
                                                        <select id="carrosserie" name="type_carrosserie" class="form-select">
                                                        <option value="vp">Voiture particulier</option>
                                                            <!-- <option value="car_tou">Carrosserie tourisme</option>
                                                            <option  value="autre_car">Autre Carrosserie</option> -->
                                                        </select>
                                                    </div>
                                                <div class="col-md-3">
                                                    <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                            <input class="form-check-input"  name="remorque[]" value="true" id="inline-5" type="checkbox">
                                                            <label class="form-check-label" for="inline-5" >Véhicule avec remorque</label>
                                                        </div>
                                                </div>
                                                

                                                    <div class="col-md-3">
                                                        <label for="poid_carrosserie" id="poid_carrosserieLabel"class="form-label" >Poid carrosserie</label>
                                                        <select id="poid_carrosserie" name="poid_carrosserie" class="form-select">
                                                            <option value="3t500">jusqu'a 3t500</option>
                                                            <option  value="3t500plus">Au de la de 3t500</option>
                                                        </select>
                                                    </div>
                                                <div>
                                                
                                                </div>
                                            <hr class="mt-4 mb-4">
                                            <h6 class="pb-3 mb-0">Garantie souhaitée</h6>

                                                <div class="col">
                                                    <div class="row justify-content-end">
                                                        <div class="col-md-3">
                                                            <label for="capitale_assure" class="form-label" >Capital assuré</label>
                                                            <select id="capitale_assure"  name="capitale_assure"  class="form-select" >
                                                                <option selected="" value="500000">500.000</option>
                                                                <option  value="1000000">1.000.000</option>
                                                                <option  value="1500000">1.500.000</option>
                                                                <option  value="2000000">2.000.000</option>
                                                                <option  value="2500000">2.500.000</option>
                                                                <option  value="3000000">3.000.000</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="option_pers_tr" id="option_pers_tr_label" class="form-label" >Option personne transportée</label>
                                                            <select id="option_pers_tr"  name="option_pers_tr"  class="form-select" >
                                                                <option selected=""  value="option1">Option 1</option>
                                                                <option  value="option2">Option 2</option>
                                                                <option  value="option3">Option 3</option>
                                                                <option  value="option4">Option 4</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="m-t-15 m-checkbox-inline">
                                                        @foreach ($garanties as $key => $garantie)
                                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                                <input class="form-check-input" name="id_garantie[]" value="{{ $garantie->id_garantie }}" id="{{ $garantie->id_garantie }}" type="checkbox"  @if($key === 0) checked @endif>
                                                                <label class="form-check-label" for="{{ $garantie->id_garantie }}">{{ $garantie->nom_garantie }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            <div class="card-footer text-end">
                                                <button class="btn btn-primary" id="boutonSimuler" type="button" onclick="simulateurFormulaire()">simuler</button>
                                            </div>
                                                <div class="row g-3" id="resultats"></div>
                                        </form>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- fin affichage simulateur  -->
    <!-- affichage souscription  -->
        <div class="modal fade bd-example-modal-lg" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addSouscription">Souscrire un client</h4>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid checkout">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Récapitulatif</h5>
                                </div> 
                                <div class="card-body">
                                    <div class="row">
                                        <form id="maSouscription" action="{{route('souscrirPack')}}"  method="post" class="form-space theme-form  ">
                                            <div class="row">
                                                <div class="checkout-details">
                                                    <div class="order-box">
                                                        <div class="title-box">
                                                            <div class="checkbox-title">
                                                            <h4>Garantie(s)</h4>
                                                            <span>Détail(s) Prime(s)</span>
                                                            </div>
                                                        </div>
                                                        <ul class="qty" id="garantiesList">
                                                        </ul>
                                                        <ul class="sub-total total">
                                                            <li>Prime nette<span class="count" id="primeNette"></span></li>
                                                            <li>Taxe <span class="count" id="taxe"></span></li>
                                                            <li>Coût de police <span class="count" id="coupDePolice"></span></li>
                                                            <li>FGA <span class="count" id="fga"></span></li>
                                                        </ul>
                                                        <ul class="sub-total">
                                                            <li>Total <span class="count" id="primeTTC"></span></li>
                                                        </ul>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- <div class="row">
                                                {{ csrf_field()}}
                                                <h6>Renseignez information assuré</h6>
                                                <div class="col-md-4">
                                                    <label class="col-form-label">Civilité</label>
                                                    <select  name="civilite" class="form-select" >
                                                        <option selected=""  value="Mr">Monsieur</option>
                                                        <option  value="Mme">Madame</option>
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
                                                <div class="col-md-4">
                                                    <label class="col-form-label">Tél</label>
                                                    <input class="form-control" name="phone" type="Number" placeholder="Ex:221771234567" required>
                                                </div>

                                                <hr class="mt-4 mb-4">
                                                <h6 class="pb-3 mb-0">Renseignez Caractéristiques véhicule</h6>	

                                                <div class="col-md-4">
                                                    <label  class="form-label" >Marque</label>
                                                        <input class="form-control" name="marque" type="text" placeholder="marque du vehicule" required>
                                                    </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" >Model</label>
                                                    <input class="form-control" name="model" type="text" name="inputPassword" placeholder="model du vehicule" required>
                                                </div>
                                                
                                                
                                                <div class="col-md-4">
                                                    <label  class="form-label" >Immatriculation/chasis</label>
                                                    <input class="form-control" name="vin" type="text" required>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="date-picker">
                                                        <label class="form-label">Date 1ere mise en circulation</label>
                                                        <input class="datepicker-here form-control digits" name="date_first_circ" type="text" data-language="fr" required>
                                                    </div>
                                                </div>
                                               
                        
                                                <div class="col-md-4">
                                                    <label for="energy" class="form-label" >Energie</label>
                                                    <input id="energy" class="form-control" name="energie" type="text" readonly >
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="forcefiscale" class="form-label" >Force Fiscale</label>
                                                    <input id="forcefiscale" class="form-control" name="force_fiscale" type="text" readonly >
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="nbrplace" class="form-label" >Nombre de place</label>
                                                    <input id="nbrplace" class="form-control" name="nombre_place" type="text" readonly >
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valeurneuf" class="form-label" >Valeur neuve</label>
                                                    <input id="valeurneuf" class="form-control" name="valeur_neuf" type="number" readonly >
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="valeurvenale" class="form-label" >Valeur vénale</label>
                                                    <input id="valeurvenale" class="form-control" name="valeur_venale" type="number" readonly >
                                                </div> 
                                                
                                                
                                                    <div>
                                                
                                                    </div>
                                                <hr class="mt-4 mb-4">
                                                <h6 class="pb-3 mb-0">Validité du contrat</h6>

                                                <div class="col-md-4  ">
                                                    <div class="date-picker">
                                                        <label class="form-label">Date d'effet</label>
                                                        <input class="datepicker-here form-control digits" name="date_effet" type="text" data-language="fr" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label  class="form-label" >Durée(en mois)</label>
                                                    <input id="duree_m" class="form-control" name="duree" type="text" readonly >
                                                </div>
                                                

                                                <hr class="mt-4 mb-4">
                                                <h6 class="pb-3 mb-0">Garantie souhaitée</h6>

                                                    <div class="col-md-4">
                                                            <label for="modalNomComplet" class="form-label" >Assurance</label>
                                                            <input id="modalNomComplet" class="form-control" name="compagnie" type="text" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modalGarantie" class="form-label" >Garantie(s)</label>
                                                            <input id="modalGarantie" class="form-control" name="garantie" type="text" readonly >
                                                        </div>
                                                       
                                                        <div class="col-md-4">
                                                            <label for="capitaleassure" class="form-label" >Capital assuré</label>
                                                            <input id="capitaleassure" class="form-control" name="capitale_assure" type="number" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modaloption" class="form-label" >Option personne transportée</label>
                                                            <input id="modaloption" class="form-control" name="option_pers_tr" type="text" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modalPrimeNet" class="form-label" >Prime Net en Fcfa</label>
                                                            <input id="modalPrimeNet" class="form-control" name="prime_net" type="number" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modalTaxe" class="form-label" >Taxe en Fcfa</label>
                                                            <input id="modalTaxe" class="form-control" name="taxe" type="number" readonly >
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <label for="modalCoupP" class="form-label" >Coût de police en Fcfa</label>
                                                            <input id="modalCoupP" class="form-control" name="coup_police" type="number" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modalFga" class="form-label" >FGA</label>
                                                            <input id="modalFga" class="form-control" name="fga" type="number" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="modalPrime" class="form-label" >Prime(TTC) en Fcfa</label>
                                                            <input id="modalPrime" class="form-control" name="prime_ttc" type="number" readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input id="modalLogo" class="form-control" name="logo" type="text" hidden readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                                <input id="catvehicule" class="form-control" name="cat_vehicule" type="text" hidden readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                                <input id="carrosseriee" class="form-control" name="type_carrosserie" type="text" hidden readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input id="modalPG" class="form-control" name="prix_garantie" type="text" hidden readonly >
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input id="modalID" class="form-control" name="id_produit" type="text" hidden  readonly >
                                                        </div>

                                                        


                                                    <div class="card-footer text-end">
                                                        <button class="btn btn-primary" id="boutonSoumettre" type="submit" >souscrire</button>
                                                    </div>                                     
                                            </div>                                          -->
                                        </form>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- fin affichage souscription  -->
@endsection

@section('script')
@include('layouts.script_simulateur')
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<!-- <script>
  
    function calculerDateEcheance() {
        var dateEffet = new Date($("#date_effet").val());
        var dureeMois = parseInt($("#duree_m").val());

            if (!isNaN(dureeMois)) {
            var dateEcheance = new Date(dateEffet.getFullYear(), dateEffet.getMonth() + dureeMois - 1, dateEffet.getDate());
            var jour = ("0" + dateEcheance.getDate()).slice(-2);
            var mois = ("0" + (dateEcheance.getMonth() + 1)).slice(-2);
            var annee = dateEcheance.getFullYear();
            var dateEcheanceFormat = jour + '/' + mois + '/' + annee;

            $("#date_echeance").val(dateEcheanceFormat);
        }else {
            // Durée invalide
            $("#date_echeance").val("");
            alert("La durée ne peut pas dépasser 12 mois.");
        }
    }

    // Événement de changement de la durée
    $("#duree_m").on("change", function () {
        calculerDateEcheance();
    });

    // Événement de changement de la date d'effet
    $("#date_effet").on("changeDate", function () {
        calculerDateEcheance();
    });
   
</script> -->

@endsection