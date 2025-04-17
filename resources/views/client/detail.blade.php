@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

    <style>
                /* Style the Image Used to Trigger the Modal */
        #myImg,#myImg1 {
          border-radius: 5px;
          cursor: pointer;
          transition: 0.3s;
        }

        .rotate-btn {
              position: absolute;
              top: 50%;
              background-color: rgba(0, 0, 0, 0.5);
              border: none;
              color: white;
              font-size: 20px;
              cursor: pointer;
              padding: 10px;
              z-index: 2;
              transform: translateY(-50%);
          }

          #rotateLeft, #rotateLeft1 {
              left: 10px;
          }

          #rotateRight, #rotateRight1 {
              right: 10px;
          }

        #myImg:hover {opacity: 0.7;}
        #myImg1:hover {opacity: 0.7;}
        /* The Modal (background) */
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          padding-top: 100px; /* Location of the box */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
          margin: auto;
          display: block;
          width: 80%;
          max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption,#caption1 {
          margin: auto;
          display: block;
          width: 80%;
          max-width: 700px;
          text-align: center;
          color: #ccc;
          padding: 10px 0;
          height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
          animation-name: zoom;
          animation-duration: 0.6s;
        }
        .modal-content, #caption1 {
          animation-name: zoom;
          animation-duration: 0.6s;
        }

        @keyframes zoom {
          from {transform:scale(0)}
          to {transform:scale(1)}
        }

        /* The Close Button */
        .close,.close1 {
          position: absolute;
          top: 15px;
          right: 35px;
          color: #f1f1f1;
          font-size: 40px;
          font-weight: bold;
          transition: 0.3s;
        }

        .close:hover,.close:focus {
          color: #bbb;
          text-decoration: none;
          cursor: pointer;
        }
        .close1:hover,.close1:focus {
          color: #bbb;
          text-decoration: none;
          cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
          .modal-content {
            width: 100%;
          }
        }
         
    </style>
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@if($client[0]->statut == 1)
<h3>Details client</h3>
@elseif($client[0]->statut == 0)
<h3>Details prospect</h3>
@endif

@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    @if($client[0]->statut == 1)
    <li class="breadcrumb-item">Client</li>
    @elseif($client[0]->statut == 0)
    <li class="breadcrumb-item">Prospect</li>
    @endif
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
        @if(count($client)>0)
          <div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
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
        @auth
            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                  @if( $client[0]->client->id_partenaire != null)
                  <div class="row">
                    <div class="col-xl-12">
                            <form action="">
                                  <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Partenaire</h4>
                                        <!-- <div class="">
                                              <button class="btn btn-primary btn-block">Enregistrer</button>
                                        </div> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                          <div class="profile-title">
                                            <div class="media"><img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg') }}">
                                              <div class="media-body">
                                                <h5 class="mb-2">{{$partenaire->civilite}}{{$partenaire->first_name}} {{$partenaire->last_name}}</h5>
                                                <!-- <p><strong>Profession: </strong> {{$client[0]->client->profession}}</p> -->
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                              <div class="mb-4">
                                                <label class="form-label">E-mail</label>
                                                <input class="form-control"  value="{{$partenaire->email}}" readonly>
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="mb-4">
                                                <label class="form-label">Téléphone</label>
                                                <input class="form-control"  type="text" value="{{$partenaire->phone}}" readonly>
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="mb-4">
                                                <label class="form-label">commission</label>
                                                <input class="form-control"  value="{{$partenaire->commission_rate}}%" readonly>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                            </form>
                          </div>
                    </div>
                  <div class="row">
                    <div class="col-xl-4">
                        <form action="/update-client-saving/{{$client[0]->client->id}}">
                            {{ csrf_field()}}
                            @method('put')
                              <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Profil client</h4>
                                    <div class="">
                                          <button class="btn btn-primary btn-block">Enregistrer</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                      <div class="profile-title">
                                        <div class="media"><img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg') }}">
                                          <div class="media-body">
                                            <h5 class="mb-2">{{$client[0]->client->civilite}}{{$client[0]->client->name}}</h5>
                                            <p><strong>Profession: </strong> {{$client[0]->client->profession}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                      <label class="form-label">E-mail</label>
                                      <input class="form-control" name="email" value="{{$client[0]->client->email}}">
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label">Téléphone</label>
                                      <input class="form-control" name="contact" type="tel" value="{{$client[0]->client->contact}}{{$client[0]->client->phone}}">
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label">Adresse</label>
                                      <input class="form-control" name="adrss" value="{{$client[0]->client->adrss}}">
                                    </div>
                                </div>
                              </div>
                        </form>
                    </div>
                    <div class="col-xl-8">
                      <form class="card" action="/update-vehicule-saving/{{$client[0]->id_talon}}">
                      {{ csrf_field()}}
                      @method('put')
                        <div class="card-header">
                          <h4 class="card-title mb-0">Information véhicule</h4>               
                            <div class="text-end">
                                <button class="btn btn-primary" type="submit">Mise à jour véhicule</button>
                            </div>

                          <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Marque</label>
                              <input class="form-control" name="marque" value="{{$client[0]->marque}}" >
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Modèle</label>
                              <input class="form-control" name="model" value="{{$client[0]->model}}" >
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Energie</label>
                              <input class="form-control" value="{{$client[0]->energie}}" readonly>
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Immatriculation/Chassis</label>
                              <input class="form-control" name="vin" value="{{$client[0]->vin}}" >
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Nombre de place</label>
                              <input class="form-control"  value="{{$client[0]->nombre_place}}" readonly >
                            </div>
                            
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Catégorie véhicule</label>
                              <input class="form-control"  value="{{$client[0]->type_carrosserie}}" readonly >
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Date 1ére mise en circulation</label>
                              <input class="form-control" value="{{$client[0]->date_first_circ}}" readonly >
                            </div>
                            </div>@foreach($packages->produits as $item)
                          @if($item->garantie->nom_court=== "tcmp")
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Valeur neuve</label>
                              <input class="form-control" value="{{$client[0]->valeur_neuf}} Fcfa" readonly>
                            </div>
                            </div>
                            @endif
                        @endforeach
                        @foreach($packages->produits as $item)
                          @if($item->garantie->nom_court === "vol" || $item->garantie->nom_court === "icd")
                            <div class="col-md-3">
                            <div class="mb-3">
                              <label class="form-label">Valeur vénale</label>
                              <input class="form-control" value="{{$client[0]->valeur_venale}} Fcfa" readonly>
                            </div>
                            </div>
                            @endif
                        @endforeach
                            
                          </div>
                        </div>
                        
                        <div class="card-footer text-end">
                          @if($client[0]->livraison == 0 && $client[0]->statut == 0 )
                            <div class="row">
                            @if($client[0]->mail_count !== 0)
                            <span>Envoyée le,{{ $client[0]->updated_at->format('d/m/Y à H\hi') }}</span>
                            @endif
                              <h6 class="mb-2">Relance(s): {{$client[0]->mail_count}} </h6>
                            </div>
                            @endif
                          
                          @if ($client[0]->livraison == 0 && $client[0]->statut == 0 )
                          <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                          <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir devis</a>
                          <a href="/mail_finalistion/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Envoyer finalisation</a>
                          @elseif ($client[0]->livraison == 0 && $client[0]->statut == 1 )
                          <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                              <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                              @if($client[0]->capital_dece !== NULL)
                          <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                          @endif
                              <a href="/renvoi_facture/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Renvoyer facture</a>
                              <a href="/lancer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Lancer la livraison</a>
                          @elseif ($client[0]->livraison == 1 && $client[0]->statut == 1)
                          <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                            <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                            @if($client[0]->capital_dece !== NULL)
                          <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                          @endif
                              <a href="/annuler/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Reprogrammer livraison</a>
                              <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livraison effectuée</a>
                          @elseif ($client[0]->livraison == 2 && $client[0]->statut == 1)
                          <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                          <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                          @if($client[0]->capital_dece !== NULL)
                          <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                          @endif
                              <!-- <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livrée</a> -->
                          @endif
                          <!-- <a href="/contrat/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Générer contrat</a> -->
                          </div>
                        </form>
                    </div>
                  </div>
                        
                  @elseif( $client[0]->client->id_partenaire == null)
                        <div class="col-xl-4">
                          <form action="/update-client-saving/{{$client[0]->client->id}}">
                              {{ csrf_field()}}
                              @method('put')
                                <div class="card">
                                  <div class="card-header">
                                      <h4 class="card-title mb-0">Profil client</h4>
                                      <div class="">
                                            <button class="btn btn-primary btn-block">Enregistrer</button>
                                      </div>
                                  </div>
                                  <div class="card-body">
                                      <div class="row mb-2">
                                        <div class="profile-title">
                                          <div class="media"><img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg') }}">
                                            <div class="media-body">
                                              <h5 class="mb-2">{{$client[0]->client->civilite}}{{$client[0]->client->name}}</h5>
                                              <p><strong>Profession: </strong> {{$client[0]->client->profession}}</p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="mb-3 mt-3">
                                        <label class="form-label">E-mail</label>
                                        <input class="form-control" name="email" value="{{$client[0]->client->email}}">
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Téléphone</label>
                                        <input class="form-control" name="contact" type="number" value="{{$client[0]->client->contact}}{{$client[0]->client->phone}}">
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Adresse</label>
                                        <input class="form-control" name="adrss" value="{{$client[0]->client->adrss}}">
                                      </div>
                                  </div>
                                </div>
                          </form>
                        </div>
                        <div class="col-xl-8">
                          <form class="card" action="/update-vehicule-saving/{{$client[0]->id_talon}}">
                          {{ csrf_field()}}
                          @method('put')
                            <div class="card-header">
                              <h4 class="card-title mb-0">Information véhicule</h4>               
                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit">Mise à jour véhicule</button>
                                </div>

                              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Marque</label>
                                  <input class="form-control" name="marque" value="{{$client[0]->marque}}" >
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Modèle</label>
                                  <input class="form-control" name="model" value="{{$client[0]->model}}" >
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Energie</label>
                                  <input class="form-control" value="{{$client[0]->energie}}" readonly>
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Immatriculation/Chassis</label>
                                  <input class="form-control" name="vin" value="{{$client[0]->vin}}" >
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Nombre de place</label>
                                  <input class="form-control"  value="{{$client[0]->nombre_place}}" readonly >
                                </div>
                                
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Catégorie véhicule</label>
                                  <input class="form-control"  value="{{$client[0]->type_carrosserie}}" readonly >
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Date 1ére mise en circulation</label>
                                  <input class="form-control" value="{{$client[0]->date_first_circ}}" readonly >
                                </div>
                                </div>@foreach($packages->produits as $item)
                              @if($item->garantie->nom_court=== "tcmp")
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Valeur neuve</label>
                                  <input class="form-control" value="{{$client[0]->valeur_neuf}} Fcfa" readonly>
                                </div>
                                </div>
                                @endif
                            @endforeach
                            @foreach($packages->produits as $item)
                              @if($item->garantie->nom_court === "vol" || $item->garantie->nom_court === "icd")
                                <div class="col-md-3">
                                <div class="mb-3">
                                  <label class="form-label">Valeur vénale</label>
                                  <input class="form-control" value="{{$client[0]->valeur_venale}} Fcfa" readonly>
                                </div>
                                </div>
                                @endif
                            @endforeach
                                
                              </div>
                            </div>
                            
                            <div class="card-footer text-end">
                              @if($client[0]->livraison == 0 && $client[0]->statut == 0 )
                                <div class="row">
                                @if($client[0]->mail_count !== 0)
                                <span>Envoyée le,{{ $client[0]->updated_at->format('d/m/Y à H\hi') }}</span>
                                @endif
                                  <h6 class="mb-2">Relance(s): {{$client[0]->mail_count}} </h6>
                                </div>
                                @endif
                              
                              @if ($client[0]->livraison == 0 && $client[0]->statut == 0 )
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                              <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir devis</a>
                              <a href="/mail_finalistion/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Envoyer finalisation</a>
                              @elseif ($client[0]->livraison == 0 && $client[0]->statut == 1 )
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                                  <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                                  @if($client[0]->capital_dece !== NULL)
                              <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                              @endif
                                  <a href="/renvoi_facture/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Renvoyer facture</a>
                                  <a href="/lancer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Lancer la livraison</a>
                              @elseif ($client[0]->livraison == 1 && $client[0]->statut == 1)
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                                <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                                @if($client[0]->capital_dece !== NULL)
                              <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                              @endif
                                  <a href="/annuler/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Reprogrammer livraison</a>
                                  <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livraison effectuée</a>
                              @elseif ($client[0]->livraison == 2 && $client[0]->statut == 1)
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                              <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                              @if($client[0]->capital_dece !== NULL)
                              <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                              @endif
                                  <!-- <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livrée</a> -->
                              @endif
                              <!-- <a href="/contrat/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Générer contrat</a> -->
                              </div>
                            </form>
                        </div>
                  @endif
            @elseif(Auth::check() && (Auth::user()->role === 'partenaire'))
                  <div class="col-xl-4">
                    <form action="/update-client-saving/{{$client[0]->client->id}}">
                        {{ csrf_field()}}
                        @method('put')
                          <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Profil client</h4>
                                <div class="">
                                      <button class="btn btn-primary btn-block">Enregistrer</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                  <div class="profile-title">
                                    <div class="media"><img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/user/7.jpg') }}">
                                      <div class="media-body">
                                        <h5 class="mb-2">{{$client[0]->client->civilite}}{{$client[0]->client->name}}</h5>
                                        <p><strong>Profession: </strong> {{$client[0]->client->profession}}</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="mb-3 mt-3">
                                  <label class="form-label">E-mail</label>
                                  <input class="form-control" name="email" value="{{$client[0]->client->email}}">
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Téléphone</label>
                                  <input class="form-control" name="contact" type="number" value="{{$client[0]->client->contact}}{{$client[0]->client->phone}}">
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Adresse</label>
                                  <input class="form-control" name="adrss" value="{{$client[0]->client->adrss}}">
                                </div>
                            </div>
                          </div>
                    </form>
                  </div>
                  <div class="col-xl-8">
                    <form class="card" action="/update-vehicule-saving/{{$client[0]->id_talon}}">
                    {{ csrf_field()}}
                    @method('put')
                      <div class="card-header">
                        <h4 class="card-title mb-0">Information véhicule</h4>               
                          <div class="text-end">
                              <button class="btn btn-primary" type="submit">Mise à jour véhicule</button>
                          </div>

                        <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Marque</label>
                            <input class="form-control" name="marque" value="{{$client[0]->marque}}" >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Modèle</label>
                            <input class="form-control" name="model" value="{{$client[0]->model}}" >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Energie</label>
                            <input class="form-control" value="{{$client[0]->energie}}" readonly>
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Immatriculation/Chassis</label>
                            <input class="form-control" name="vin" value="{{$client[0]->vin}}" >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Nombre de place</label>
                            <input class="form-control"  value="{{$client[0]->nombre_place}}" readonly >
                          </div>
                          
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Catégorie véhicule</label>
                            <input class="form-control"  value="{{$client[0]->type_carrosserie}}" readonly >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Date 1ére mise en circulation</label>
                            <input class="form-control" value="{{$client[0]->date_first_circ}}" readonly >
                          </div>
                          </div>@foreach($packages->produits as $item)
                        @if($item->garantie->nom_court=== "tcmp")
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Valeur neuve</label>
                            <input class="form-control" value="{{$client[0]->valeur_neuf}} Fcfa" readonly>
                          </div>
                          </div>
                          @endif
                      @endforeach
                      @foreach($packages->produits as $item)
                        @if($item->garantie->nom_court === "vol" || $item->garantie->nom_court === "icd")
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Valeur vénale</label>
                            <input class="form-control" value="{{$client[0]->valeur_venale}} Fcfa" readonly>
                          </div>
                          </div>
                          @endif
                      @endforeach
                          
                        </div>
                      </div>
                      
                      <div class="card-footer text-end">
                        @if($client[0]->livraison == 0 && $client[0]->statut == 0 )
                          <div class="row">
                          @if($client[0]->mail_count !== 0)
                          <span>Envoyée le,{{ $client[0]->updated_at->format('d/m/Y à H\hi') }}</span>
                          @endif
                            <h6 class="mb-2">Relance(s): {{$client[0]->mail_count}} </h6>
                          </div>
                          @endif
                        
                        @if ($client[0]->livraison == 0 && $client[0]->statut == 0 )
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                        <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir devis</a>
                        <a href="/mail_finalistion/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Envoyer finalisation</a>
                        @elseif ($client[0]->livraison == 0 && $client[0]->statut == 1 )
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                            <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                            @if($client[0]->capital_dece !== NULL)
                        <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                        @endif
                            <a href="/renvoi_facture/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Renvoyer facture</a>
                            <a href="/lancer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Lancer la livraison</a>
                        @elseif ($client[0]->livraison == 1 && $client[0]->statut == 1)
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                          <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                          @if($client[0]->capital_dece !== NULL)
                        <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                        @endif
                            <a href="/annuler/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Reprogrammer livraison</a>
                            <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livraison effectuée</a>
                        @elseif ($client[0]->livraison == 2 && $client[0]->statut == 1)
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                        <a href="/facture_client/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Voir facture</a>
                        @if($client[0]->capital_dece !== NULL)
                        <a href="/condition/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Contrat</a>
                        @endif
                            <!-- <a href="/terminer/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Livrée</a> -->
                        @endif
                        <!-- <a href="/contrat/{{$client[0]->client->id}}/{{$client[0]->id_talon}}" class="btn btn-primary">Générer contrat</a> -->
                        </div>
                      </form>
                  </div>
            @endif
        @endauth     
                @if ($client[0]->statut == 1 )
                <div class="col-xl-12">
                @auth
                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                  <form class="card" action="/update-talon-saving/{{$client[0]->id_talon}}">
                    {{ csrf_field()}}
                    @method('put')
                    <div class="card">
                      <div class="card-header">
                        <h4 class="card-title mb-0">Conditions particulières</h4>
                        <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                        @foreach($packages->produits as $item)
                        @if($item->garantie->nom_court=== "sr")
                        <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label"><Sect></Sect>Sécurité routière décès(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->capital_dece}}" name="capital_dece" >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label"><Sect></Sect>Sécurité routière invalidité(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->capital_invalidite}}" name="capital_invalidite" >
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Frais médicaux(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->frais_medicaux}}" name="frais_medicaux" >
                          </div>
                          </div>
                          @endif
                        @if($item->garantie->nom_court=== "vol")
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Franchise vol(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->franchise_vol}}" name="franchise_vol" >
                          </div>
                          </div>
                          @endif
                          @if($item->garantie->nom_court=== "tcmp")
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Franchise tout risque(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->franchise_tr}}" name="franchise_tr" >
                          </div>
                          </div>
                          @endif
                          @if($item->garantie->nom_court=== "br")
                          <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Franchise bris de glace(F CFA)</label>
                            <input class="form-control" type="number" value="{{$client[0]->franchise_bdg}}" name="franchise_bdg" >
                          </div>
                          </div>
                          @endif
                      @endforeach
                          
                          <div class="text-end">
                              <button class="btn btn-primary" type="submit">Enregistrer et envoyer</button>
                          </div> 
                        </div>
                      </div>
                    </div>
                  </form>
                       @endif
                        @endauth
                </div>
                @endif
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title mb-0">récapitulatifs Assurance </h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Compagnie</th>
                            <th>Véhicule</th>
                            <th>Puissance fiscale</th>
                            <th>Durée</th>
                            <th class="text-right">Validité</th>
                            <!-- <th>Taxe</th> -->
                            <!-- <th class="text-right" style="text-align:right">Total Nette</th> -->
                            <!-- <th class="text-right">Total TTC</th> -->
                        </tr>
                    
                        </thead>
                        <tbody>
                        <tr>
                        <td>{{$package[0]->compagnie->nom_complet}}</td>
                            <td>{{$package[0]->marque}} {{$package[0]->model}}</td>
                            <td>{{$package[0]->force_fiscale}} CV</td>
                            <td>{{$package[0]->duree}}</td>
                            <td class="text-right">{{$package[0]->date_effet}} au {{$package[0]->date_echeance}}</td>
                            <!-- <td>14%</td> -->
                            <!-- <td>{{$package[0]->prime_net}} Fcfa</td> -->
                        </tr>
                        </tbody>
                    
                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">Garantie(s)</th>
                            <th class="text-right" scope="col">Prime(s)</th>
                            
                            </tr>
                        </thead>
                        <tbody>@foreach($packages->produits as $item)
                                        <tr>
                                            <td>
                                                {{$item->garantie->nom_garantie}}<br>
                                            </td>
                                            <td class="text-right">{{number_format($item->pivot->prix, 0, ',', ' ')}} F CFA</td>  
                                        </tr>
                                    @endforeach
                        </tbody>
                    </table><br><br>
                    @if(!empty($package[0]->image_recto) && !empty($package[0]->image_verso))
                      <div class="card-body pb-0"> 
                        <div class="details-bookmark text-center">
                          <div class="row" id="bookmarkData">
                            <div  class="col-xl-3 col-md-4 xl-50">
                              <div class="card card-with-border bookmark-card o-hidden">
                                  <div class="details-website">
                                  <img id="myImg1" class="img-fluid" src="{{ asset('storage/image/carte_grise/' . $package[0]->client_id . '/' . $package[0]->image_recto) }}" alt="">
                                    <div class="desciption-data">
                                        <div class="title-bookmark">
                                          <h6 class="title_1">Carte grise recto</h6>
                                        </div>
                                        
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="col-xl-3 col-md-4 xl-50">
                              <div class="card card-with-border bookmark-card o-hidden">
                                  <div class="details-website">
                                  <img id="myImg" class="img-fluid" src="{{ asset('storage/image/carte_grise/' . $package[0]->client_id . '/' . $package[0]->image_verso) }}" alt="">
                                    <div class="desciption-data">
                                        <div class="title-bookmark">
                                          <h6 class="title_0">Carte grise verso</h6>
                                        </div>
                                </div>
                              </div>
                            </div>
                            <!-- The Modal recto -->
                              <div id="myModal1" class="modal">

                                <!-- The Close Button -->
                                <span class="close1">&times;</span>
                                <!-- Buttons for rotation -->
                                <button id="rotateLeft1" class="rotate-btn">&larr;</button>
                                <button id="rotateRight1" class="rotate-btn">&rarr;</button>
                                <!-- Modal Content (The Image) -->
                                <img class="modal-content" id="img02">

                                <!-- Modal Caption (Image Text) -->
                                <div id="caption1"></div>
                              </div>
                            <!-- The Modal recto --> 

                            <!-- The Modal verso -->
                              <div id="myModal" class="modal">

                                <!-- The Close Button -->
                                <span class="close">&times;</span>
                                <!-- Buttons for rotation -->
                                <button id="rotateLeft" class="rotate-btn">&larr;</button>
                                <button id="rotateRight" class="rotate-btn">&rarr;</button>
                                <!-- Modal Content (The Image) -->
                                <img class="modal-content" id="img01">

                                <!-- Modal Caption (Image Text) -->
                                <div id="caption"></div>
                              </div>
                            <!-- The Modal verso --> 
                      </div>
                    @endif       
                      <div class="row">
                        
                        @foreach($packages->produits as $item)
                        @if($item->garantie->nom_court=== "ar")
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Capital avance sur recours</label>
                          <input class="form-control" value="{{$client[0]->capitale_assure}} Fcfa" readonly>
                        </div>
                        </div>
                        @endif
                        @endforeach
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Personnes transportées</label>
                          <input class="form-control" value="{{$client[0]->option_pers_tr}}" readonly>
                        </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Date d'effet</label>
                          <input class="form-control" value="{{$client[0]->date_effet}}" readonly>
                        </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Durée</label>
                          <input class="form-control" value="{{$client[0]->duree}}" readonly>
                        </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Date d'écheance</label>
                          <input class="form-control" value="{{$client[0]->date_echeance}}" readonly>
                        </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Date d'établissement</label>
                          <input class="form-control" value="{{$client[0]->date_etablissement}}" readonly >
                        </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Prime nette</label>
                          <input class="form-control" value="{{$client[0]->prime_net}} Fcfa" readonly>
                        </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Coût de police</label>
                            <input class="form-control" value="{{$client[0]->coup_police}} Fcfa" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                            <label class="form-label">Taxes</label>
                            <input class="form-control" value="{{$client[0]->taxe}} Fcfa " readonly>
                          </div>
                        </div>
                        <!-- <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Bonus commercial</label>
                          <input class="form-control" value="{{$client[0]->bonus_commercial}}% " readonly>
                        </div>
                        </div> -->
                        
                        <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Prime TTC</label>
                          <input class="form-control" value="{{$client[0]->prime_ttc}} Fcfa" readonly>
                        </div>
                        </div>
                        
                        <div class="col-md-3">
                        <!-- <div class="mb-3">
                          <label class="form-label">Numéro de police</label>
                          <input class="form-control" value="{{$client[0]->n_police}}" readonly> -->
                        <!-- </div> -->
                        </div>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @else

            le client n'existe pas

        @endif
@endsection

@section('script')

<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<script>
      // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("myImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function(){
          modal.style.display = "block";
          modalImg.src = this.src;
          captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        function rotateImage(img, degrees) {
          const currentRotation = img.getAttribute('data-rotation') || 0;
          const newRotation = parseInt(currentRotation) + degrees;
          img.style.transform = `rotate(${newRotation}deg)`;
          img.setAttribute('data-rotation', newRotation);
      }

      document.getElementById('rotateLeft').onclick = function() {
          rotateImage(document.getElementById('img01'), -90);
      }

      document.getElementById('rotateRight').onclick = function() {
          rotateImage(document.getElementById('img01'), 90);
      }

      document.getElementById('rotateLeft1').onclick = function() {
          rotateImage(document.getElementById('img02'), -90);
      }

      document.getElementById('rotateRight1').onclick = function() {
          rotateImage(document.getElementById('img02'), 90);
      }

      // Get the modal
      var modal = document.getElementById("myModal");

      // Get the image and insert it inside the modal - use its "alt" text as a caption
      var img = document.getElementById("myImg");
      var modalImg = document.getElementById("img01");
      var captionText = document.getElementById("caption");
      img.onclick = function() {
          modal.style.display = "block";
          modalImg.src = this.src;
          captionText.innerHTML = this.alt;
          modalImg.setAttribute('data-rotation', 0);
          modalImg.style.transform = 'rotate(0deg)';
      }

      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];

      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
          modal.style.display = "none";
      }

      var modal1 = document.getElementById("myModal1");

      var img1 = document.getElementById("myImg1");
      var modalImg1 = document.getElementById("img02");
      var captionText1 = document.getElementById("caption1");
      img1.onclick = function() {
          modal1.style.display = "block";
          modalImg1.src = this.src;
          captionText1.innerHTML = this.alt;
          modalImg1.setAttribute('data-rotation', 0);
          modalImg1.style.transform = 'rotate(0deg)';
      }

      var span1 = document.getElementsByClassName("close1")[0];

      span1.onclick = function() {
          modal1.style.display = "none";
      }
</script>

@endsection