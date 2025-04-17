@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>{{$compagnie->abr }} Assurance</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item">Compagnies Assurances</li>
    <li class="breadcrumb-item active">Profile</li>
@endsection
 
@section('content') 
<!-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
              <h4 class="modal-title" id="addproduit">Update photo</h4>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="card">
                      <div class="card-body">
                          <form action="/update-profile-compagnie-saving/{{$compagnie->id}}" method="post" enctype="multipart/form-data" >
                          {{ csrf_field()}}
                          @method('put')
                              <div class="row g-3">
                                  
                              <div class="col-md-6">
                              <div class="mb-3">
                                  <label>Photo compagnie</label>
                                  <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload"  data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image_filename"/>
                              </div>
                          </div>
                                  
                                  
                                  
                                  
                                  
                              </div>
                              <div class="row g-3">
                              
                              
                                  
                                  
                                  <button class="btn btn-primary" type="submit">Enregistrer</button>
                              </div>
                              
                          </form>
                      </div>
                  </div>
              </div>
                  
          </div>
      </div>
</div> -->
<div class="container-fluid">
    <div class="user-profile">
        <div class="row">
            <!-- user profile first-style start-->
            <div class="col-sm-12">
                <div class="card hovercard text-center">
                    <div class="cardheader"></div>
                    <div class="user-image">
                        <div  class="avatar"><img src="{{asset('/storage/image/logo/' .$compagnie->logo);}}" name="logo" alt="{{$compagnie->name}}"></div>
                        <!-- <div class="icon-wrapper">
                        <a href="" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"><i class="icofont icofont-pencil-alt-5"></i></a>
                        </div> -->
                        
                    </div>
                    <div class="info">
                        <div class="row">
                            <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ttl-info text-start">
                                            <h6><i class="fa fa-envelope"></i>E-mail</h6>
                                            <span>{{$compagnie->mail_1 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="ttl-info text-start">
                                            <h6><i class="fa fa-phone"></i>Contact</h6><span>{{$compagnie->tel_1 }}</span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="ttl-info text-start">
                                            <h6><i class="fa fa-calendar"></i>   BOD</h6><span>02 January 1988</span>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                                <div class="user-designation">
                                    <div class="title"><h1  >{{$compagnie->nom_complet }}</h1></div>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="ttl-info text-start">
                                            <h6><i class="fa fa-location-arrow"></i>Location</h6><span>{{$compagnie->address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="social-media">
                            <ul class="list-inline">
                                <li class="list-inline-item"><a href="https://www.facebook.com/" target="_blank"><i
                                            class="fa fa-facebook"></i></a></li>
                                <li class="list-inline-item"><a href="https://accounts.google.com/" target="_blank"><i
                                            class="fa fa-google-plus"></i></a></li>
                                <li class="list-inline-item"><a href="https://twitter.com/" target="_blank"><i
                                            class="fa fa-twitter"></i></a></li>
                                <li class="list-inline-item"><a href="https://www.instagram.com/" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                                <li class="list-inline-item"><a href="https://rss.app/" target="_blank"><i
                                            class="fa fa-rss"></i></a></li>
                            </ul>
                        </div>
                        <div class="follow">
                            <div class="row">
                                <div class="col-6 text-md-end border-right">
                                    <div class="follow-num counter">25869</div><span>Follower</span>
                                </div>
                                <div class="col-6 text-md-start">
                                    <div class="follow-num counter">659887</div><span>Following</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row starter-main">
        <div class="card-body btn-showcase">
            <!-- Large modal add produit auto-->
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="addproduit">Ajouter Produit</h4>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="/AddProduit" method="post">
                                        {{ csrf_field()}}
                                        <input name="compagnie_id" type="hidden"  value="{{$compagnie->id}}">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="cat_vehicule" class="form-label" >Catégorie Véhicule</label>
                                                    <select id="cat_vehicule" name="cat_vehicule" class="form-select" >
                                                        <option value="cat1">Véhicule particulier</option>
                                                        <!-- <option value="cat2">Véhicule de commerce</option> -->
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" >Garantie</label>
                                                    <select id="garantie" name="id_garantie" class="form-select" >
                                                    @foreach ($garantie as $data)
                                                                <option value="{{ $data->id_garantie}}" >{{ $data->nom_garantie }} </option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="option_pers_tr" id="option_pers_tr_label" class="form-label" >Option personne transportée</label>
                                                    <select id="option_pers_tr"  name="option_pers_tr"  class="form-select" >
                                                        <option selected=""  value="">...</option>
                                                        <option  value="option1">Option 1</option>
                                                        <option  value="option2">Option 2</option>
                                                        <option  value="option3">Option 3</option>
                                                        <option  value="option4">Option 4</option>
                                                    </select>
                                                </div>
                                                
                                                
                                                
                                                
                                            </div>
                                            <div class="row g-3">
                                            <div class="col-md-4">
                                                    <label for="energie" id="energieLabel" class="form-label" >Energie</label>
                                                    <select id="energie" name="energie" class="form-select" onchange="changerOptions()" >
                                                        <option selected="" value="" >...</option>
                                                        <option value="essence">Essence</option>
                                                        <option  value="diesel">Diesel</option>
                                                    </select>
                                                </div>
                                            <div class="col-md-4">
                                                    <label for="fc" id="fcLabel" class="form-label" >Puissance Fiscale</label>
                                                    <select id="fc" name="force_fiscale[]"  class="form-select" multiple >
                                                    <option selected="" value="" >...</option>
                                                        <!-- <option   value="2">2</option>
                                                        <option  value="3-6">3-6</option>
                                                        <option  value="7-10">7-10</option>
                                                        <option  value="11-14">11-14</option>
                                                        <option  value="15-23">15-23</option>
                                                        <option  value="24">24</option> -->
                                                    </select>
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <label class="form-label" >Remorque</label>
                                                    <input class="form-control"  name="remorque"  type="number" step="0.01">
                                                </div> -->
                                                <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" >Prime</label>
                                                    <input class="form-control" name="prime"  type="number" step="0.01" required="">
                                                </div>
                                                <div class="col-md-4">
                                                        <label for="carrosserie" id="carrosserieLabel" class="form-label" >Type carrosserie</label>
                                                        <select id="carrosserie" name="type_carrosserie" class="form-select">
                                                        <option selected="" value="" >...</option>
                                                        <option  value="vp">Voiture particulier</option>
                                                            <!-- <option value="car_tou">Carrosserie tourisme</option>
                                                            <option  value="autre_car">Autre Carrosserie</option> -->
                                                        </select>
                                                    </div>
                                                <!-- <div class="col-md-4">
                                                    <label for="carrosserie" id="carrosserieLabel" class="form-label" >Carrosserie</label>
                                                    <select id="carrosserie" name="type_carrosserie" class="form-select">
                                                    <option selected="" value="" >...</option>
                                                        <option value="car_tou">Carrosserie tourisme</option>
                                                        <option  value="autre_car">Autre Carrosserie</option>
                                                    </select>
                                                </div> -->
                                                <div class="col-md-4">
                                                    <label for="poid_carrosserie" id="poid_carrosserieLabel"class="form-label" >Carrosserie</label>
                                                    <select id="poid_carrosserie" name="poid_carrosserie" class="form-select">
                                                    <option selected="" value="" >...</option>
                                                        <option value="3t500">jusqu'a 3t500</option>
                                                        <option  value="3t500plus">Au de la d 3t500</option>
                                                    </select>
                                                </div>
                                                
                                                
                                                </div>
                                                
                                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                </div>
            <!--end modal-->

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warninghome" role="tab" aria-controls="pills-warninghome" aria-selected="true">
                        <i class="icofont icofont-ui-home"></i>Produits automobile</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" id="pills-warninghome-tab" data-bs-toggle="pill" href="#pills-warningtrip" role="tab" aria-controls="pills-warninghome" aria-selected="false">
                        <i class="icofont icofont-ui-home"></i>Produits voyage</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill" href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile" aria-selected="false">
                        <i class="icofont icofont-man-in-glasses"></i>Souscription automobile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill" href="#pills-warningprosous" role="tab" aria-controls="pills-warningprosous" aria-selected="false">
                        <i class="icofont icofont-man-in-glasses"></i>Souscription voyage</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" id="pills-warningcontact-tab" data-bs-toggle="pill"  href="#pills-warningcontact" role="tab" aria-controls="pills-warningcontact" aria-selected="false">
                        <i class="icofont icofont-contacts"></i>Contact</a>
                </li> -->
            </ul>
            <div class="tab-content" id="pills-warningtabContent">
                <!-- content produit auto-->
                        <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel" aria-labelledby="pills-warninghome-tab"><br>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Nouveau produit</button>
                            </div><br>
                            <div class="col-sm-12">
                                <div class="card">
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
                                    <div class="card-block row">
                                        <div class="col-sm-12 col-lg-12 col-xl-12">
                                            <div class="table-responsive">
                                                <table class="display" id="basic-1">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">Compagnie</th>
                                                    <th scope="col">Catégorie Véhicule</th>
                                                    <!-- <th scope="col">Logo</th> -->
                                                    <th scope="col">Garantie</th>
                                                    <th scope="col">Option pers/trans</th>
                                                    <th scope="col">Puissance Fiscale</th>
                                                    <!-- <th scope="col">Type carrosserie</th> -->
                                                    <th scope="col">Prime</th>
                                                    <th scope="col">Energie</th>
                                                    <!-- <th scope="col">Remorque</th> -->
                                                    <th scope="col">modifier</th>
                                                    <th scope="col">supprimer</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    <tr>
                                                    @foreach($produit as $data)
                                                    <td>{{$compagnie->nom_complet}}</td>
                                                    <td>{{$data->cat_vehicule}}</td>
                                                    <!-- <td>Pixel@efo.com	</td> -->
                                                    <td>{{$data->garantie->nom_garantie }}</td>
                                                    <td>{{$data->option_pers_tr}}</td>
                                                    <td>{{$data->force_fiscale}}</td>
                                                    <!-- <td>{{$data->type_carrosserie}}</td> -->
                                                    <td>{{$data->prime}}</td>
                                                    <td>{{$data->energie}}</td>
                                                    <!-- <td >{{$data->remorque}}</td> -->
                                                    
                                                    <td>
                                                        <form action="{{ url('edit-produit/'.$data->id_produit)}}" method="get">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-warning"> <i class="fa fa-pencil"></i></button>
                                                        </div>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="{{ url('delete-produit/'.$data->id_produit)}}" method="get">
                                                        <div class="form-group">
                                                        <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>
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
                <!-- end content produit auto-->

                <!-- content souscription auto-->
                        <div class="tab-pane fade" id="pills-warningprofile" role="tabpanel" aria-labelledby="pills-warningprofile-tab">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive user-datatable">
                                            <table class="display" id="basic-2">
                                                <thead>
                                                    <tr>
                                                        <th>Prénom et Nom</th>
                                                        <th>E-mail</th>
                                                        <th>Tél</th>
                                                        <th>Adresse</th>
                                                        <th>Véhicules</th>
                                                        <th>Date d'effet</th>
                                                        <th>Date d'echeance</th>
                                                        <th>Durée</th>
                                                        <th>Prime TTC</th>
                                                        <th>N police</th>
                                                        <th>Etat livraison</th>  
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
                                                        <td>{{$data->date_effet}}</td>
                                                        <td>{{$data->date_echeance}}</td>
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
                                                                        echo "Acheminer";
                                                                        break;
                                                                    // Ajoutez d'autres cas selon vos besoins
                                                                    default:
                                                                        echo "En attente";
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                        <!-- <td>
                                                            <a href="{{ asset('storage/factures/' . $data->facture) }}" target="_blank">
                                                                <button class="btn btn-secondary">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                                </button>
                                                            </a>
                                                        </td> -->
                                                        <td>
                                                            <form  action="{{ url('detail-talon/'.$data->client->id.'/'.$data->id_talon)}}" method="get">
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
                <!-- end content souscription auto--> 
                 
                <!-- content souscription voyage-->
                <div class="tab-pane fade" id="pills-warningprosous" role="tabpanel" aria-labelledby="pills-warningprosous-tab">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive user-datatable">
                                            <table class="display" id="basic-3">
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
                                                        <th>Etat livraison</th>  
                                                        <th>Voir plus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($souscritV as $data)
                                                    <tr>
                                                    
                                                        <td>{{$data->client->name}} </td>
                                                        <td>{{$data->client->email}}</td>
                                                        <td>{{$data->client->contact}}</td>
                                                        <td>{{$data->client->adrss}}</td>
                                                        <td>{{$data->pays_destination}}</td>
                                                        <td>{{$data->date_depart}}</td>
                                                        <td>{{$data->date_retour}}</td>
                                                        <td>{{$data->duree}}</td>
                                                        <td>{{$data->prime_ttc}} Fcfa</td>
                                                        <td>{{$data->n_police_v}}</td>
                                                        <td>
                                                            <?php
                                                                // Supposons que $numero soit le numéro récupéré
                                                                switch ($data->livraison) {
                                                                    case 1:
                                                                        echo "En cours";
                                                                        break;
                                                                    case 2:
                                                                        echo "Acheminer";
                                                                        break;
                                                                    // Ajoutez d'autres cas selon vos besoins
                                                                    default:
                                                                        echo "En attente";
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                        <!-- <td>
                                                            <a href="{{ asset('storage/factures/' . $data->facture) }}" target="_blank">
                                                                <button class="btn btn-secondary">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                                </button>
                                                            </a>
                                                        </td> -->
                                                        <td>
                                                            <form  action="{{ url('detail-talon-v/'.$data->client->id.'/'.$data->id_talon_v)}}" method="get">
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
                <!-- end content souscription voyage--> 
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

<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

@endsection