@extends('layouts.master')
@section('breadcrumb-title')
    <!-- <h3>Default</h3> -->
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">

@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Suivis assurances</li>
@endsection

@section('content')
@auth
@if(Auth::check() && (Auth::user()->role === 'partenaire'))
<div class="container-fluid">
        <div class="row widget-grid">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Suivis des assurances</h4>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs nav-primary" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-echeance-tab" data-bs-toggle="tab" data-bs-target="#nav-echeance" type="button" role="tab" aria-controls="nav-echeance" aria-selected="true">Police à surveiller</button>
                            <button class="nav-link " id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Police en cours</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Police expirée</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Police non-livrée</button>
                        </div>
                    </nav>
                    <div class="card-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-echeance" role="tabpanel" aria-labelledby="nav-echeance-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-echeance-cours">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_en_cours_part as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-cours">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_en_cours_part as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-expire">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_expire_part as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-non-livre">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <th>Etat livraison</th>
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($police_non_livre_part as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                <td>
                                                    <?php
                                                        // Supposons que $numero soit le numéro récupéré
                                                        switch ($data->livraison) {
                                                            case 1:
                                                                echo "En cours";
                                                                break;
                                                            case 2:
                                                                echo "Livrée";
                                                                break;
                                                            // Ajoutez d'autres cas selon vos besoins
                                                            default:
                                                                echo "En attente";
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
    </div> 
@else
    <div class="container-fluid">
        <div class="row widget-grid">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Suivis des assurances</h4>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs nav-primary" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-echeance-tab" data-bs-toggle="tab" data-bs-target="#nav-echeance" type="button" role="tab" aria-controls="nav-echeance" aria-selected="true">Police à surveiller</button>
                            <button class="nav-link " id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Police en cours</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Police expirée</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Police non-livrée</button>
                        </div>
                    </nav>
                    <div class="card-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-echeance" role="tabpanel" aria-labelledby="nav-echeance-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-echeance-cours">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_echeance as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-cours">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_en_cours as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-expire">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <!-- <th>Etat livraison</th> -->
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($client_expire as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                                <div class="dt-ext table-responsive">
                                    <table class="display" id="liste-client-non-livre">
                                        <thead>
                                            <tr>
                                                <th>Prénom(s) et Nom</th>
                                                <th>E-mail</th>
                                                <th>Tél</th>
                                                <th>Adresse</th>
                                                <th>Véhicule</th>
                                                <th>Durée</th>
                                                <th>Prime TTC</th>
                                                <th>Police</th>
                                                <th>Etat livraison</th>
                                                <th>Date effet</th>
                                                <th>Date d'échéance</th>
                                                <th>Voir plus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($police_non_livre as $data)
                                            <tr>
                                            
                                                <td>{{$data->client->name}} </td>
                                                <td>{{$data->client->email}}</td> 
                                                <td>{{$data->client->contact}}</td>
                                                <td>{{$data->client->adrss}}</td>
                                                <td>{{$data->marque}} {{$data->model}}</td>
                                                <td>{{$data->duree}}</td>
                                                <td>{{$data->prime_ttc}} Fcfa</td>
                                                <td>{{$data->n_police}}</td>
                                                <td>
                                                    <?php
                                                        // Supposons que $numero soit le numéro récupéré
                                                        switch ($data->livraison) {
                                                            case 1:
                                                                echo "En cours";
                                                                break;
                                                            case 2:
                                                                echo "Livrée";
                                                                break;
                                                            // Ajoutez d'autres cas selon vos besoins
                                                            default:
                                                                echo "En attente";
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td>{{$data->date_effet}}</td>  
                                                
                                                <td>{{$data->date_echeance}}</td>  
                                                
                                                <td>
                                                    <form action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}"  method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
    </div>      

@endif
@endauth
@endsection

@section('script')
<!-- <script>
	   $(document).ready(function() {
    // Définition de la DataTable avec filtres de date
    var table = $('#liste-client').DataTable({
        dom: 'Bfrtip',
        columnDefs: [
            { type: 'date', targets: [0, 1] } // Colonnes 1 et 2 de type date
        ]
    });
    
    
    // Ajout des filtres de date
    $('#minDate').on('change', function () {
        table.draw();
    });
    $('#maxDate').on('change', function () {
        table.draw();
    });

    // Fonction de filtrage de date
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = new Date(data[0]); // Les dates sont dans la première colonne
            if ((min === "" || date >= new Date(min)) && (max === "" || date <= new Date(max))) {
                return true;
            }
            return false;
        }
    );
});
</script> -->
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>

<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/chart-custom.js')}}"></script>
<script src="{{asset('assets/js/chart/google/google-chart-loader.js')}}"></script>
<script src="{{asset('assets/js/chart/google/google-chart.js')}}"></script>

<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>
@endsection