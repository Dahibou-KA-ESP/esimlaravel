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
    <li class="breadcrumb-item active">Historiques</li>
@endsection

@section('content')

<div class="container-fluid">
	<div class="row widget-grid">
	@if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
<div class="col-md-12">
    <div class="card">
        <div class="card-header pb-0 card-no-border">
                    <h3>Historique simulations auto</h3>
        </div>
        <div class="card-body">
            <div class="dt-ext table-responsive">
                <table class="display" id="historique">
                    <thead>
                        <tr>
                            <th>Contact</th>
                            <th>Puissance fiscale (cv)</th>
                            <th>Energie</th>
                            <th>Durée</th>
                            <th>Date</th>
                            <!-- <th>Nombre de relance</th>
                            <th>Relancer</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($simulation as $data)
                        <tr>
                            <td>{{$data->contact}} </td>
                            <td>{{$data->force_fiscale}}</td> 
                            <td>{{$data->energie}}</td>
                            <td>{{$data->duree}} mois</td>
                            <!-- <td>{{$data->updated_at->format('d/m/Y  H\hi')}}</td>  -->
                            <td>{{$data->updated_at}}</td> 
                            <!-- <td></td>
                            <td></td> -->
                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-header pb-0 card-no-border">
                    <h3>Historique simulations voyage</h3>
        </div>
        <div class="card-body">
            <div class="dt-ext table-responsive">
                <table class="display" id="historique_v">
                    <thead>
                        <tr>
                            <th>Contact</th>
                            <th>Date depart</th>
                            <th>Date retour</th>
                            <th>Pays déstination</th>
                            <!-- <th>Date naissance</th> -->
                            <!-- <th>Âge</th> -->
                            <!-- <th>Durée</th> -->
                            <th>Date</th>
                            <!-- <th>Nombre de relance</th>
                            <th>Relancer</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($simulationV as $data)
                        <tr>
                            <td>{{$data->contact}} </td>
                            <td>{{$data->date_depart}}</td> 
                            <td>{{$data->date_retour}}</td>
                            <td>{{$data->paysH->Nom}}</td>
                            <!-- <td>{{$data->date_naissance}}</td> -->
                            <!-- <td>{{$data->age}} an(s)</td> -->
                            <!-- <td>{{$data->duree}} jours</td> -->
                            <!-- <td>{{$data->updated_at->format('d/m/Y  H\hi')}}</td>  -->
                            <td>{{$data->updated_at}}</td> 
                            <!-- <td></td>
                            <td></td> -->
                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

pup();
</script>

<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>

<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>




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