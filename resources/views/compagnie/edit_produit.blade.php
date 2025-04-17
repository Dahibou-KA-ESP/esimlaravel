@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Compagnies Assurances</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Compagnies Assurances</li>
@endsection

@section('content')
<div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
                
                <div class="col-xl-12">
                  
                    <div class="card-header">
                      <h4 class="card-title mb-0">modification Produit</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card">
                                    <div class="card-body">
                                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                                        <form action="/update-produit-saving/{{$produit[0]->id_produit}}" method="post">
                                        {{ csrf_field()}}
                                        @method('put')
                                      
                                            <div class="row g-3">
                                            <div class="col-md-4">
                                                    <label for="cat_vehicule" class="form-label" >Categorie Vehicule</label>
                                                    <select id="cat_vehicule" name="cat_vehicule" class="form-select" >
                                                        <option value="cat1">Vehicule particulier</option>
                                                        <option value="cat2">Vehicule de commerce</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" >Garantie</label>
                                                    <select id="garantie" name="garantie" class="form-select" >
                                                    @foreach ($garantie as $data)
                                                                <option value="{{ $data->id_garantie }}" {{ $produit[0]->id_garantie == $data->id_garantie ? 'selected' : '' }}>{{ $data->nom_garantie }} </option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="option_pers_tr" id="option_pers_tr_label" class="form-label" >Option personne transportée</label>
                                                    <select id="option_pers_tr"  name="option_pers_tr"  class="form-select" >
                                                        <option selected=""  value="{{$produit[0]->option_pers_tr}}">{{$produit[0]->option_pers_tr}}</option>
                                                        <option  value="option1">Option 1</option>
                                                        <option  value="option2">Option 2</option>
                                                        <option  value="option3">Option 3</option>
                                                        <option  value="option4">Option 4</option>
                                                    </select>
                                                </div>
                                                
                                                
                                            </div>
                                            <div class="row g-3">
                                            <div class="col-md-4">
                                                    <label for="energie" class="form-label" >Energie</label>
                                                    <select id="energie" name="energie" class="form-select" onchange="changerOptions()" >
                                                        <option selected=""  value="{{$produit[0]->energie}}">{{$produit[0]->energie}}</option>
                                                        <option value="essence">Essence</option>
                                                        <option  value="diesel">Diesel</option>
                                                    </select>
                                                </div>
                                            <div class="col-md-4">
                                                    <label for="fc" class="form-label" >Puissance Fiscale</label>
                                                    <select id="fc" name="force_fiscale" class="form-select" >
                                                    <option selected=""  value="{{$produit[0]->force_fiscale}}">{{$produit[0]->force_fiscale}}</option>
                                                        <option   value="2">2</option>
                                                        <option  value="3-6">3-6</option>
                                                        <option  value="7-10">7-10</option>
                                                        <option  value="11-14">11-14</option>
                                                        <option  value="15-23">15-23</option>
                                                        <option  value="24">24</option>
                                                    </select>
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <label class="form-label" >Remorque</label>
                                                    <input class="form-control"  name="remorque" value="{{$produit[0]->remorque}}" type="number" step="0.01">
                                                </div> -->
                                                
                                                
                                            </div>
                                            <div class="row g-3">
                                            
                                            <div class="col-md-4">
                                                    <label class="form-label" >Prime</label>
                                                    <input class="form-control" name="prime" value="{{$produit[0]->prime}}"  type="number" step="0.01" required="">
                                                </div>
                                                 <div class="col-md-4">
                                                    <label for="carrosserie" id="carrosserieLabel" class="form-label" >Carrosserie</label>
                                                    <select id="carrosserie" name="type_carrosserie" class="form-select">
                                                        <option selected=""  value="{{$produit[0]->type_carrosserie}}">{{$produit[0]->type_carrosserie}}</option>
                                                        <option value="vp">Voiture particulier</option>
                                                        <option value="car_tou">Carrosserie tourisme</option>
                                                        <option  value="autre_car">Autre Carrosserie</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="poid_carrosserie" id="poid_carrosserieLabel"class="form-label" >Carrosserie</label>
                                                    <select id="poid_carrosserie" name="poid_carrosserie" class="form-select">
                                                        <option selected="" value="" >...</option>
                                                        <option value="3t500">jusqu'a 3t500</option>
                                                        <option  value="3t500plus">Au de la d 3t500</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="card-footer text-end">
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                              <button class="btn btn-primary" type="submit">Sauvegarder</button>
                            </div>
                                    
                                </div>
                            
                          </form>
                </div>
                
              </div>
            </div>
          </div>
        </div>
@endsection

@section('script')
<script>
    function toggleSecondSelect() {
        var firstSelectValue = $('#garantie').val();
        if (firstSelectValue === '1') {
            $('#carrosserieLabel, #carrosserie').show().prop('required', true);
           

        } else {
            $('#carrosserieLabel, #carrosserie').hide().prop('required', false);
           

        }
    }

    // Initial toggle based on the default value of the first select
    toggleSecondSelect();
    // Event listener to toggle the second select when the first select changes
    $('#garantie').on('change', toggleSecondSelect);


    function toggleSecondSelect1() {
        var firstSelectValue = $('#garantie').val();
        if (firstSelectValue === '4' || firstSelectValue === '1') {
            $('#energieLabel, #energie').show();
            $('#fcLabel, #fc').show();

        } else {
            $('#energieLabel, #energie').hide();
            $('#fcLabel, #fc').hide();

        }
    }

    // Initial toggle based on the default value of the first select
    toggleSecondSelect1();
    // Event listener to toggle the second select when the first select changes
    $('#garantie').on('change', toggleSecondSelect1);



    function toggleSecondSelect3() {
        var firstSelectValue = $('#garantie').val();
        if (firstSelectValue === '5') {
            $('#option_pers_tr_label, #option_pers_tr').show();
        } else {
            $('#option_pers_tr_label, #option_pers_tr').hide();
        }
    }

    // Initial toggle based on the default value of the first select
    toggleSecondSelect3();
    // Event listener to toggle the second select when the first select changes
    $('#garantie').on('change', toggleSecondSelect3);
</script>
@endsection