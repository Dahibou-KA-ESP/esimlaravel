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
    <h3>Packages</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Packages</li>
@endsection

@section('content')
<div class="container-fluid">
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
    <div class="row starter-main">
        <div class="card-body btn-showcase">
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="addproduit">Ajouter pack</h4>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form id="monFormulaire" action="{{route('addPackage')}}" method="post" class="form-space theme-form row"  enctype="multipart/form-data">
                                                                {{ csrf_field()}}
                                                            
                                                            <h6 class="pb-3 mb-0">Information package</h6>
                                                                <div class="col-md-6">
                                                                    <label class="col-form-label">Nom</label>
                                                                    <input class="form-control" name="nom" type="text" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                     <label class="col-form-label">Nom court</label>
                                                                    <input class="form-control" name="nom_court" type="text" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="col-form-label">Description</label>
                                                                    <textarea class="form-control" name="description" rows="4" required></textarea>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label>Logo du pack: </label>
                                                                    <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" name="logo_pack"/>
                                                                </div>
                                                            <hr class="mt-4 mb-4"> 
                                                            <h6 class="pb-3 mb-0">Garantie(s) souhaitée(s)</h6>

                                                                <div class="col">
                                                                <div class="m-t-15 m-checkbox-inline">
                                                                @foreach($garanties as $gar)
                                                                    <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                                        <input class="form-check-input" name="garanties[]" value="{{$gar->id_garantie}}" id="{{$gar->id_garantie}}" type="checkbox"   >
                                                                        <label class="form-check-label" for="{{$gar->id_garantie}}">{{$gar->nom_garantie}}</label>
                                                                    </div>
                                                                @endforeach
                                                                </div>
                                                                </div>
                                                            <div class="card-footer text-end">
                                                                <button class="btn btn-primary" id="boutonPackage" type="submit" >Créer package</button>
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
                    </div>
                </div>
            <!--end modal-->
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warninghome" role="tab" aria-controls="pills-warninghome" aria-selected="true">
                    <i class="icofont icofont-ui-home"></i>Nos packs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill" href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile" aria-selected="false">
                    <i class="icofont icofont-man-in-glasses"></i>Simulation packages</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" id="pills-warningcontact-tab" data-bs-toggle="pill"  href="#pills-warningcontact" role="tab" aria-controls="pills-warningcontact" aria-selected="false">
                    <i class="icofont icofont-contacts"></i>Contact</a>
            </li> -->
        </ul>
        <div class="tab-content" id="pills-warningtabContent">

            <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel" aria-labelledby="pills-warninghome-tab"><br>
                
                <div class="col-sm-12">
                <div class="card">
                        <div class="card-header">
                        <h5>Nos packages</h5>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Nouveau pack</button>
                        </div>
                        <div class="card-body">
                        <div class="row g-sm-4 g-3 ">
                            @foreach($packages as $data)
                                <div class="col-xxl-4 col-md-6">
                                
                                <div class="prooduct-details-box">                                
                                    <div class="media">
                                    <div class="media-body ms-3">
                                        <div class="product-name">
                                        <div class="form-check form-switch ">
                                            <input class="form-check-input" type="checkbox" role="switch" id="pack_{{$data->id_pack}}" onclick="togglePackageStatus(<?php echo $data->id_pack; ?>)" {{ $data->statut == 1 ? 'checked' : '' }} >
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Activer/Desactiver</label>
                                        </div>
                                        <h6><a href="#">{{$data->nom}}</a></h6>
                                        </div>
                                        
                                        <div class="price d-flex"> 
                                        {{$data->description}}
                                        </div>
                                        <div class="avaiabilty">
                                        </div>
                                        <div>
                                            <a class="btn-primary btn-xs btn-pack"  href="{{ url('edit-package/'.$data->id_pack) }}"> <i class="fa fa-pencil"></i></a>
                                            <a class="btn-danger btn-xs btn-pack" href="{{ url('delete-package/'.$data->id_pack) }}"> <i class="fa fa-trash"></i></a>
                                        </div>
                                        
                                    </div>
                                    </div>
                                </div>
                                </div>
                            @endforeach
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade " id="pills-warningprofile" role="tabpanel" aria-labelledby="pills-warningprofile-tab">
                <div class="col-sm-12">
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
                                                <label  class="form-label" >Numero de téléphone</label>
                                                <input id="" class="form-control" name="contact" type="number"  required >
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
                                            <div class="col-md-3">
                                                <input  class="form-control" name="" type="" hidden>
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
                                                    
                                            <button class="btn btn-primary"  type="submit" >simuler</button>
                                        </div>
                                                </div>
                                        
                                    </form>
                                </div>
                            </div>
            </div>
                    
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function togglePackageStatus(idpack) {
        $('#pack_'+idpack).change(function() {
            var isChecked = $(this).prop('checked');
            var packageId = idpack // Remplacez par l'ID réel du package
            
            // Envoyer une requête AJAX pour mettre à jour le statut du package
            $.ajax({
                url: '/update-package-status/' + packageId,
                method: 'POST',
                data: { statut: isChecked ? 1 : 0 ,
                        _token:"{{csrf_token()}}"
                }, // 1 pour activer, 0 pour désactiver
                success: function(response) {
                    // console.log(response.success)
                    location.reload();

                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la mise à jour du statut du package :', error);
                }
            });
        });
    }
</script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
@endsection