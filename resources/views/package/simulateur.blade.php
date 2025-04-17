@extends('layouts.master')

@section('title', 'Edit Profile')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/scss" href="{{asset('assets/css/layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/cdn_css_intel.css')}}">
    @endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Simulateur automobile</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Simulateur automobile</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <form id="monPack" action="{{route('simulatePack')}}" method="post" class="form-space theme-form row"> 
                {{ csrf_field()}}
            <h6 class="pb-3 mb-0">Caracteristiques vehicule</h6>
                <div class="col-md-3">
                        <label for="cat_vehicule" class="form-label" >Catégorie Vehicule</label>
                        <select id="cat_vehiculeP" name="cat_vehicule" class="form-select" >
                            <option value="cat1">Vehicule particulier</option>
                            <!-- <option value="cat2">Vehicule de commerce</option> -->
                        </select>
                </div>
                <div class="col-md-3">
                    <label for="energie" class="form-label" >Energie</label>
                    <select id="energieP" name="energie" class="form-select" onchange="changerOptions()" >
                        <option selected=""  value="essence">Essence</option>
                        <option  value="diesel">Diesel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="force_fiscale" class="form-label" >Force Fiscale</label>
                    <select id="force_fiscaleP" name="force_fiscale" class="form-select" >
                        <option selected=""  value="2">2</option>
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
                    <label  class="form-label" >Numéro téléphone</label></br>
                    <input id="phone" class="form-control" name="contact" type="tel"  required> 
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
                                <option value="{{ $data->nom }}" {{ ($data->nom === 1) ? 'selected' : '' }}>{{ $data->nom }} mois</option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-3">
                        <label for="carrosserie" id="carrosserieLabel" class="form-label" >Type carrosserie</label>
                        <select id="carrosserieP" name="type_carrosserie" class="form-select">
                        <option value="vp">Voiture particulier</option>
                            <!-- <option value="car_tou">Carrosserie tourisme</option>
                            <option  value="autre_car">Autre Carrosserie</option> -->
                        </select>
                    </div>
                    
                    <!-- <div class="col-md-3 ">
                        <div class="date-picker">
                            <label class="form-label">Date 1ere mise en circulation</label>
                            <input class="datepicker-here form-control digits" id="date_first_circ" name="date_first_circ" type="text" data-language="fr" readonly >
                        </div>
                    </div> -->
                    <div class="col-md-3 ">
                        <div class="date-picker">
                            <label class="form-label">Date 1ere mise en circulation</label>
                            <input class="form-control" id="date_first_circ" name="date_first_circ" type="date">
                        </div>
                    </div>
                <div class="col-md-3">
                    <label  class="form-label" >Valeur neuve</label>
                    <input id="valeur_neuf" class="form-control" name="valeur_neuf" type="number">
                </div>
                <div class="col-md-3">
                    <label  class="form-label" >Valeur vénale</label>
                    <input id="valeur_venale" class="form-control" name="valeur_venale" type="number">
                </div>
                

                
                    <div>
                    <div class="card-footer text-end">
                    <button class="btn btn-primary" type="button" id="validate-button">Simuler</button>
                    <button type="submit" style="display:none;" id="hidden-submit">Submit</button>
                <!-- <button class="btn btn-primary"  type="submit" >simuler</button> -->
            </div>
                    </div>
            
        </form>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
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
</script>
@endsection