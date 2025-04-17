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
    <li class="breadcrumb-item active">Suivis sponsoring</li>
@endsection

@section('content')
@auth

<div class="container-fluid">
    <div class="row widget-grid">
        <div class="row">
            @foreach($sponsoring2 as $source => $weeks)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Source: {{ $source }}
                        </div>
                        <div class="card-body">
                            @if($weeks->isNotEmpty())
                                @foreach($weeks as $week => $group)
                                    <p>
                                        Semaine {{ $week }}: {{ $group->count() }} parrainages
                                    </p>
                                @endforeach
                            @else
                                <p>Aucun parrainage pour cette source.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-body">
            <nav>
                <div class="nav nav-tabs nav-primary" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-echeance-tab" data-bs-toggle="tab" data-bs-target="#nav-echeance" type="button" role="tab" aria-controls="nav-echeance" aria-selected="true">Sponsoring</button>
                </div>
            </nav>
            <div class="card-body">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-echeance" role="tabpanel" aria-labelledby="nav-echeance-tab" tabindex="0">
                        <div class="dt-ext table-responsive">
                            <table class="display" id="basic-12" >
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Country</th>
                                        <th>ipaddress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsoring as $data)
                                        <tr>                                           
                                            <td>{{$data->source}} </td>
                                            <td>{{$data->country}}</td> 
                                            <td>{{$data->ipadress}}</td>   
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