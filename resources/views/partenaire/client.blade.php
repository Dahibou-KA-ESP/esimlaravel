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
    <li class="breadcrumb-item active">Clients</li>
@endsection

@section('content')

<div class="container-fluid">
<h3>Clients partenaire</h3>
	<div class="col-sm-12">
		<div class="col-xxl-auto col-xl-12 col-sm-6 box-col-6"> 
			<div class="row"> 
				<div class="col-xl-6"> 
					<div class="card widget-1">
						<div class="card-body"> 
						<div class="widget-content">
							<div class="widget-round secondary">
							<div class="bg-round">
								<svg class="svg-fill">
								<use href="{{ asset('assets/svg/icon-sprite.svg#cart') }}"> </use>
								</svg>
								<svg class="half-circle svg-fill">
								<use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
								</svg>
							</div>
							</div>
							<div> 
							<h6>Client(s)</h6><h4 class="f-light">{{ $numberOfClientsPart }}</h4>
							</div>
						</div>
						<!-- <div class="font-secondary f-w-500"><i class="icon-arrow-up icon-rotate me-1"></i><span>+50%</span></div> -->
						</div>
					</div>
				</div>
				<div class="col-xl-6"> 
					<div class="card widget-1">
					<div class="card-body"> 
						<div class="widget-content">
						<div class="widget-round success">
							<div class="bg-round">
							<svg class="svg-fill">
								<use href="{{ asset('assets/svg/icon-sprite.svg#rate') }}"> </use>
							</svg>
							<svg class="half-circle svg-fill">
								<use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
							</svg>
							</div>
						</div>
						<div> 
							<h6 class="mb-1">Production du jour</h6><h4 class="f-light">{{$numberOfClientsDayPart}}</h4>
						</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<div class="col-xxl-auto col-xl-12 col-sm-6 box-col-6">
		<div class="row"> 
			<div class="col-xxl-4 col-xl-4 box-col-4">
				<div class="card widget-1 widget-with-chart">
					<div class="card-body"> 
					<div> 
						<h6>Primes encaissées du jour</h6><h4 class="f-light">{{ number_format($TotalEncaisseDayPart, 0, ',', ' ') }} FCFA</h4>
					</div>
					<!-- <div class="order-chart"> 
						<div id="orderchart"></div>
					</div>  -->
					</div>
				</div>
			</div>
			<div class="col-xxl-4 col-xl-4 box-col-4">
				<div class="card widget-1 widget-with-chart">
					<div class="card-body"> 
					<div> 
						<h6 class="mb-1">Prime totale {{$annee}}</h6><h4 class="f-light">{{ number_format($TotalEncaissePart, 0, ',', ' ') }} FCFA</h4>
					</div>
					<!-- <div class="profit-chart"> 
						<div id="profitchart"></div>
					</div> -->
					</div>
				</div>
			</div>
			<div class="col-xxl-4 col-xl-4 box-col-4">
				<div class="card widget-1 widget-with-chart">
					<div class="card-body"> 
					<div> 
						<h6 class="mb-1">Comission partenaire {{$annee}}</h6><h4 class="f-light">{{ number_format($commission_partenaire, 0, ',', ' ') }} FCFA</h4>
					</div>
					<!-- <div class="profit-chart"> 
						<div id="profitchart"></div>
					</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="card">
			<!-- <div class="card-header pb-0 card-no-border">
                        <h3>Liste des clients du partenaire</h3>
			</div> -->
			<div class="card-body">
				<div class="dt-ext table-responsive">
					<table class="display" id="liste-client">
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
						@foreach($souscritpart as $data)
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

@endsection

@section('script')
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