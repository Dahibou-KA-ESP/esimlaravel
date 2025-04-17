@extends('layouts.master')
@section('breadcrumb-title')
    <!-- <h3>Default</h3> -->
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />



@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Clients</li>
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
	  <div class="col-xxl-4 col-sm-6 box-col-6">
		<div class="card profile-box">
		  <div class="card-body">
			<div class="media">
			  <div class="media-body"> 
				<div class="greeting-user">
				  <h6 class="f-w-600">Bienvenue sur Platine Comparateur</h6>
				  <p>Ici vous pouvez comparer differentes assurances et voir les meilleures offres</p>
				  
				</div>
			  </div>
			  <div>  
				<div class="clockbox">
				  <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
					<g id="face">
					  <circle class="circle" cx="300" cy="300" r="253.9"></circle>
					  <path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6"></path>
					  <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
					</g>
					<g id="hour"> 
					  <path class="hour-hand" d="M300.5 298V142"></path>
					  <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
					</g>
					<g id="minute">
					  <path class="minute-hand" d="M300.5 298V67"></path>
					  <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
					</g>
					<g id="second">
					  <path class="second-hand" d="M300.5 350V55"></path>
					  <circle class="sizing-box" cx="300" cy="300" r="253.9">   </circle>
					</g>
				  </svg>
				</div>
				<div class="badge f-10 p-0" id="txt"></div>
			  </div>
			</div>
			
		  </div>
		</div>
	  </div>
	<div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6"> 
		<div class="row"> 
			<div class="col-xl-12"> 
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
		</div>
		</div>
		<div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6"> 
		<div class="row"> 

			<div class="col-xl-12"> 
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
				<div class="col-xxl-4 col-xl-4 box-col-12">
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
				<div class="col-xxl-4 col-xl-4 box-col-12">
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
				<div class="col-xxl-4 col-xl-4 box-col-12">
					<div class="card widget-1 widget-with-chart">
						<div class="card-body"> 
						<div> 
							<h6 class="mb-1">Commission {{$annee}}</h6><h4 class="f-light">{{ number_format($commissionPart, 0, ',', ' ') }} FCFA</h4>
						</div>
						<!-- <div class="profit-chart"> 
							<div id="profitchart"></div>
						</div> -->
						</div>
					</div>
				</div>
		</div>
	</div>

	<!-- <div class="col-sm-12 col-xl-6 box-col-6">
			<div class="card">
				<div class="card-header">
					<h5>Pie Chart <span class="digits">1</span></h5>
				</div>
				<div class="card-body p-0 chart-block">
					<div class="chart-overflow" id="pie-chart1"></div>
				</div>
			</div>
	</div> -->

	<div class="col-sm-12 col-xl-12 box-col-12">
		<div class="card">
			<div class="card-header">
				<h4>Productions {{$annee}}</h4>
			</div>
			<div class="card-body">
				<div id="area-spaline1"></div>
			</div>
		</div>
	</div>
	
	<!-- <div class="col-sm-12 col-xl-6 box-col-6">
			<div class="card">
				<div class="card-header">
					<h4>Column Chart </h4>
				</div>
				<div class="card-body">
					<div id="column-chart"></div>
				</div>
			</div>
	</div> -->

	<div class="col-sm-12">
		<div class="card">
			<div class="card-header pb-0 card-no-border">
                        <h3>Liste des clients</h3>
			</div>
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


	<!-- <div class="col-xxl-4 col-xl-7 col-md-6 col-sm-5 box-col-6">
	<div class="card height-equal"> 
		<div class="card-header card-no-border"> 
		<div class="header-top">
			<h4>Recent Orders</h4>
			<div class="card-header-right-icon">
			<div class="dropdown icon-dropdown">
				<button class="btn dropdown-toggle" id="recentdropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
				<div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentdropdown"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
			</div>
			</div>
		</div>
		</div>
		<div class="card-body pt-0">
		<div class="row recent-wrapper">
			<div class="col-xl-6">
			<div class="recent-chart"> 
				<div id="recentchart"></div>
			</div>
			</div>
			<div class="col-xl-6"> 
			<ul class="order-content">
				<li> <span class="recent-circle bg-primary"> </span>
				<div> <span class="f-light f-w-500">Cancelled </span>
					<h6 class="mt-1 mb-0">2,302<span class="f-light f-14 f-w-400 ms-1">(Last 6 Month) </span></h6>
				</div>
				</li>
				<li> <span class="recent-circle bg-info"></span>
				<div> <span class="f-light f-w-500">Delivered</span>
					<h6 class="mt-1 mb-0">9,302<span class="f-light f-14 f-w-400 ms-1">(Last 6 Month) </span></h6>
				</div>
				</li>
			</ul>
			</div>
		</div>
		</div>
	</div>
	</div> -->
</div>
@else
<div class="container-fluid">
	<div class="row widget-grid">
	@if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
	<div class="col-xxl-4 col-sm-6 box-col-6">
	<div class="card profile-box">
		<div class="card-body">
		<div class="media">
			<div class="media-body"> 
			<div class="greeting-user">
				<h6 class="f-w-600">Bienvenue sur Platine Comparateur</h6>
				<p>Ici vous pouvez comparer differentes assurances et voir les meilleures offres</p>
				
			</div>
			</div>
			<div>  
			<div class="clockbox">
				<svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
				<g id="face">
					<circle class="circle" cx="300" cy="300" r="253.9"></circle>
					<path class="hour-marks" d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6"></path>
					<circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
				</g>
				<g id="hour"> 
					<path class="hour-hand" d="M300.5 298V142"></path>
					<circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
				</g>
				<g id="minute">
					<path class="minute-hand" d="M300.5 298V67"></path>
					<circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
				</g>
				<g id="second">
					<path class="second-hand" d="M300.5 350V55"></path>
					<circle class="sizing-box" cx="300" cy="300" r="253.9">   </circle>
				</g>
				</svg>
			</div>
			<div class="badge f-10 p-0" id="txt"></div>
			</div>
		</div>
		
		</div>
	</div>
	</div>

	<div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6"> 
		<div class="row"> 
			<div class="col-xl-12"> 
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
						<h6>Simulations</h6><h4 class="f-light">{{ $numberOfSim }}</h4>
					</div>
				</div>
				</div>
			</div>
			<div class="col-xl-12"> 
				<div class="card widget-1">
				<div class="card-body"> 
					<div class="widget-content">
					<div class="widget-round primary">
						<div class="bg-round">
						<svg class="svg-fill">
							<use href="{{ asset('assets/svg/icon-sprite.svg#tag') }}"> </use>
						</svg>
						<svg class="half-circle svg-fill">
							<use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
						</svg>
						</div> 
					</div>
					<div> 
						<h6>Souscriptions</h6><h4 class="f-light">{{ $numberOfClients }}</h4>
					</div>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<div class="col-xxl-auto col-xl-3 col-sm-6 box-col-6"> 
		<div class="row"> 
			<div class="col-xl-12"> 
			<div class="card widget-1">
				<div class="card-body"> 
				<div class="widget-content">
					<div class="widget-round warning">
					<div class="bg-round">
						<svg class="svg-fill">
						<use href="{{ asset('assets/svg/icon-sprite.svg#return-box') }}"> </use>
						</svg>
						<svg class="half-circle svg-fill">
						<use href="{{ asset('assets/svg/icon-sprite.svg#halfcircle') }}"></use>
						</svg>
					</div>
					</div>
					<div> 
					<h6>Prospect(s)</h6><h4 class="f-light">{{ $numberProspect }}</h4>
					</div>
				</div>
				</div>
			</div>
			<div class="col-xl-12"> 
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
						<h6 class="mb-1">Production du jour</h6><h4 class="f-light">{{$numberOfClientsDay}}</h4>
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
			<div class="col-xxl-12 col-xl-4 box-col-12">
			<div class="card widget-1 widget-with-chart">
				<div class="card-body"> 
				<div> 
					<h6>Primes encaissées du jour</h6><h4 class="f-light">{{ number_format($TotalEncaisseDay, 0, ',', ' ') }} FCFA</h4>
				</div>
				<!-- <div class="order-chart"> 
					<div id="orderchart"></div>
				</div>  -->
				</div>
			</div>
			</div>
			<div class="col-xxl-12 col-xl-4 box-col-12">
				<div class="card widget-1 widget-with-chart">
					<div class="card-body"> 
					<div> 
						<h6 class="mb-1">Prime totale {{$annee}}</h6><h4 class="f-light">{{ number_format($TotalEncaisse, 0, ',', ' ') }} FCFA</h4>
					</div>
					<!-- <div class="profit-chart"> 
						<div id="profitchart"></div>
					</div> -->
					</div>
				</div>
			</div>
			<div class="col-xxl-12 col-xl-4 box-col-12">
				<div class="card widget-1 widget-with-chart">
					<div class="card-body"> 
					<div> 
						<h6 class="mb-1">Commission {{$annee}}</h6><h4 class="f-light">{{ number_format($commission, 0, ',', ' ') }} FCFA</h4>
					</div>
					<!-- <div class="profit-chart"> 
						<div id="profitchart"></div>
					</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-12 col-xl-12 box-col-12"> 
		<div class="card growth-wrap">
			<div class="card-header card-no-border">
			<div class="header-top">
				<h5>Evolution des clients</h5>
				<!-- <div class="dropdown icon-dropdown">
				<button class="btn dropdown-toggle" id="growthdropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
				<div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthdropdown"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
				</div> -->
			</div>
			</div>
			<div class="card-body pt-0">
			<div class="growth-wrapper">
				<div id="growthchart"></div>
			</div>
			</div>
		</div>
	</div>






	</div>
	<div class="col-sm-12 col-xl-12 box-col-12">
		<div class="card">
			<div class="card-header">
				<h4>Productions {{$annee_p}}	
					<!-- <form  action="{{ route('getAnnee') }}" method="GET">
						<select name="annee" id="annee">
							@for($i = 2023; $i <= \Carbon\Carbon::now()->year; $i++)
								<option value="{{ $i }}">{{ $i }}</option>
							@endfor
						</select>
						<button class="btn btn-primary" type="submit">Voir</button>
					</form> -->
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6" id="area-spaline"></div>
					<div class="col-md-6" id="area-spaline2"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header pb-0 card-no-border">
							<h3>Liste des Souscriptions</h3>
				</div>
				<ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
				<li class="nav-item">
							<a class="nav-link active" id="pills-warningprofile1-tab" data-bs-toggle="pill" href="#pills-warningprofile1" role="tab" aria-controls="pills-warningprofile1" aria-selected="true">
								<i class="icofont icofont-man-in-glasses"></i>Automobiles</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="pills-warningprosous1-tab" data-bs-toggle="pill" href="#pills-warningprosous1" role="tab" aria-controls="pills-warningprosous1" aria-selected="false">
								<i class="icofont icofont-man-in-glasses"></i>Voyages</a>
						</li>
				</ul>
				<div class="tab-content" id="pills-warningtabContent1">
						<!-- content souscription auto-->
							<div class="tab-pane fade show active" id="pills-warningprofile1" role="tabpanel" aria-labelledby="pills-warningprofile1-tab">
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
													<th>Mode paiement</th>
													<th>Etat livraison</th>
													<th>Date effet</th>
													<th>Date d'échéance</th>
													<th>Voir plus</th>
												</tr>
											</thead>
											<tbody>
												@foreach($souscrit as $data)
													<tr>
												
													<td>{{$data->client->name}} </td>
													<td>{{$data->client->email}}</td> 
													<td>{{$data->client->contact}}</td>
													<td>{{$data->client->adrss}}</td>
													<td>{{$data->marque}} {{$data->model}}</td>
													<td>{{$data->duree}}</td>
													<td>{{$data->prime_ttc}} Fcfa</td>
													<td>{{$data->n_police}}</td>
													<td>{{$data->paiementW->nom}}</td>
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
						<!-- end content souscription auto--> 

						<!-- content souscription voyage-->
							<div class="tab-pane fade" id="pills-warningprosous1" role="tabpanel" aria-labelledby="pills-warningprosous1-tab">
								<div class="card-body">
									<div class="dt-ext table-responsive">
										<table class="display" id="liste-client-v" >
											<thead>
												<tr>
													<th>Prénom et Nom</th>
													<th>E-mail</th>
													<th>Tél</th>
													<th>Adresse</th>
													<th>Pays déstination</th>
													<th>Date depart</th>
													<th>Date retour</th>
													<th>Durée</th>
													<th>Prime TTC</th>
													<th>N police</th>
													<th>Mode paiement</th>
													<!-- <th>Etat</th>   -->
													<th>Voir plus</th>
												</tr>
											</thead>
											<tbody>
											@foreach($souscritv as $data)
												<tr>
												
												<td>{{$data->name}} </td>
												<td>{{$data->email}}</td>
												<td>{{$data->contact}}</td>
												<td>{{$data->adrss}}</td>
												<td>{{$data->pays_destination}}</td>
												<td>{{$data->date_depart}}</td>
												<td>{{$data->date_retour}}</td>
												<td>{{$data->duree}}</td>
												<td>{{$data->prime_ttc}} Fcfa</td>
												<td>{{$data->n_police_v}}</td>
												<td>{{$data->paiementO->nom}}</td>
												<!-- <td>NON PAYER</td> -->
													<!-- <td>{{$data->n_police}}</td> -->
													<!-- <td>{{$data->updated_at->format('d/m/Y à H\hi')}}</td>  -->
													<!-- <td>{{$data->updated_at}}</td>  -->

													<td>
														<form action="{{ url('detail-talon-v/'.$data->client->id.'/'.$data->id_talon_v)}}"  method="get">
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
						<!-- end content souscription voyage--> 
					</div>
				
			</div>
		</div>
		
		<div class="col-sm-4 col-xl-4 box-col-4">
				<div class="card">
					<div class="card-header">
						<h5>Ventes et paiements</h5>
					</div>
					<div class="card-body chart-block">
						<div class="row">
							<div class="chart-overflow col-md-12" id="pie-chart3"></div>
							<div class="chart-overflow col-md-12" id="pie-chart1"></div>
							<div class="chart-overflow col-md-12" id="pie-chart2"></div>
						</div>
						<div class="row">
							<!-- <div class="chart-overflow col-md-6" id="pie-chart3"></div> -->
							<!-- <div class="chart-overflow col-md-6" id="pie-chart4"></div> -->
						</div>
						
					</div>
				</div>
		</div>
	</div>
	

	<!-- <div class="row">
		<div class="col-md-6">
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

								</tr>
							</thead>
							<tbody>
							@foreach($simulation as $data)
								<tr>
									<td>{{$data->contact}} </td>
									<td>{{$data->force_fiscale}}</td> 
									<td>{{$data->energie}}</td>
									<td>{{$data->duree}} mois</td>
									<td>{{$data->updated_at}}</td> 
									
								</tr>
								@endforeach
							</tbody>
							
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
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
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
							@foreach($simulationV as $data)
								<tr>
									<td>{{$data->contact}} </td>
									<td>{{$data->date_depart}}</td> 
									<td>{{$data->date_retour}}</td>
									<td>{{$data->paysH->Nom}}</td>
									<td>{{$data->updated_at}}</td> 
								</tr>
								@endforeach
							</tbody>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header pb-0 card-no-border">
                        <h3>Liste des prospects</h3>
			</div>
				<ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
					<!-- <li class="nav-item">
						<a class="nav-link" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warningtrip" role="tab" aria-controls="pills-warninghome" aria-selected="false">
							<i class="icofont icofont-ui-home"></i>Produits voyage</a>
					</li> -->
					<li class="nav-item">
						<a class="nav-link active" id="pills-warningprofile-tab" data-bs-toggle="pill" href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile" aria-selected="true">
							<i class="icofont icofont-man-in-glasses"></i>Automobiles</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-warningprosous-tab" data-bs-toggle="pill" href="#pills-warningprosous" role="tab" aria-controls="pills-warningprosous" aria-selected="false">
							<i class="icofont icofont-man-in-glasses"></i>Voyages</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link" id="pills-warningcontact-tab" data-bs-toggle="pill"  href="#pills-warningcontact" role="tab" aria-controls="pills-warningcontact" aria-selected="false">
							<i class="icofont icofont-contacts"></i>Contact</a>
					</li> -->
				</ul>
				<div class="tab-content" id="pills-warningtabContent">
					<!-- content souscription auto-->
						<div class="tab-pane fade show active" id="pills-warningprofile" role="tabpanel" aria-labelledby="pills-warningprofile-tab">
							<div class="card-body">
								<div class="dt-ext table-responsive">
									<table class="display" id="liste-souscription">
										<thead>
											
											<tr>
												<th>Prénoms et Nom</th>
												<th>E-mail</th>
												<th>Tél</th>
												<th>Adresse</th>
												<th>Véhicule</th>
												<th>Durée</th>
												<th>Prime TTC</th>
												<!-- <th>Police</th> -->
												<th>Date</th>
												<th>Voir plus</th>
											</tr>
										</thead>
										<tbody>
										@foreach($nonsouscrit as $data)
											<tr>
											
												<td>{{$data->client->name}} </td>
												<td>{{$data->client->email}}</td> 
												<td>{{$data->client->contact}}</td>
												<td>{{$data->client->adrss}}</td>
												<td>{{$data->marque}} {{$data->model}}</td>
												<td>{{$data->duree}}</td>
												<td>{{$data->prime_ttc}} Fcfa</td>
												<!-- <td>{{$data->n_police}}</td> -->
												<!-- <td>{{$data->updated_at->format('d/m/Y à H\hi')}}</td>  -->
												<td>{{$data->updated_at}}</td> 

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
                	<!-- end content souscription auto--> 

					<!-- content souscription voyage-->
						<div class="tab-pane fade" id="pills-warningprosous" role="tabpanel" aria-labelledby="pills-warningprosous-tab">
							<div class="card-body">
								<div class="dt-ext table-responsive">
									<table class="display" id="liste-souscription-v" >
										<thead>
											<tr>
												<th>Prénom et Nom</th>
												<th>E-mail</th>
												<th>Tél</th>
												<th>Adresse</th>
												<th>Pays déstination</th>
												<th>Date depart</th>
												<th>Date retour</th>
												<th>Durée</th>
												<th>Prime TTC</th>
												<th>N police</th>
												<th>Etat</th>  
												<th>Voir plus</th>
											</tr>
										</thead>
										<tbody>
										@foreach($nonsouscritV as $data)
											<tr>
											
											<td>{{$data->name}} </td>
											<td>{{$data->email}}</td>
											<td>{{$data->contact}}</td>
											<td>{{$data->adrss}}</td>
											<td>{{$data->pays_destination}}</td>
											<td>{{$data->date_depart}}</td>
											<td>{{$data->date_retour}}</td>
											<td>{{$data->duree}}</td>
											<td>{{$data->prime_ttc}} Fcfa</td>
											<td>{{$data->n_police_v}}</td>
											<td>NON PAYER</td>
												<!-- <td>{{$data->n_police}}</td> -->
												<!-- <td>{{$data->updated_at->format('d/m/Y à H\hi')}}</td>  -->
												<!-- <td>{{$data->updated_at}}</td>  -->

												<td>
													<form action="{{ url('detail-talon-v/'.$data->client->id.'/'.$data->id_talon_v)}}"  method="get">
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
					<!-- end content souscription voyage--> 
				</div>
				
			
		</div>
	</div>
	<!-- <div id="map" style="height: 500px;"></div> -->
	
@endif
@endauth
@endsection

@section('script')

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([14.4974, -14.4524], 5); // Centré sur le Sénégal

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ajouter un marqueur pour le Sénégal (exemple)
    L.marker([14.4974, -14.4524]).addTo(map)
        .bindPopup('Sponsoring enregistré au Sénégal')
        .openPopup();
</script>

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