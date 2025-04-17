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
<h3>Compagnies Assurances</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Compagnies Assurances</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row starter-main">
      
        <div class="card-body btn-showcase">
            
                <!-- Large modal-->
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Nouvelle compagnie</button>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="addcompagnie">Ajouter compagnie</h4>
                              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <form action="/AddCompagnie" method="post" class="theme-form mega-form" enctype="multipart/form-data">
                                    {{ csrf_field()}}
                                    <h6>Informations Assurances</h6>
                                    <div class="mb-3">
                                        <label class="col-form-label">Compagnie</label>
                                        <input class="form-control" name="nom_complet" type="text" placeholder="Nom assurance" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Adresse</label>
                                        <input class="form-control" name="address" type="text" placeholder="Entrer une adrresse" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">E-mail</label>
                                        <input class="form-control"name="mail_1" type="email" placeholder="Entrer email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Téléphone</label>
                                        <input class="form-control" name="tel_1" type="number" placeholder="Numero telephone" required>
                                    </div>
                                    <hr class="mt-4 mb-4">
                                    <h6>Informations Supplementaires</h6>
                                    <div class="mb-3">
                                        <label class="col-form-label">Ville</label>
                                        <input class="form-control" name="ville" type="text" placeholder="Ex:Dakar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Code Postal</label>
                                        <input class="form-control" name="cp" type="number" placeholder="Ex:33655">
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">fax</label>
                                        <input class="form-control" name="fax" type="text" >
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Abréviation</label>
                                        <input class="form-control" name="abr" type="text" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Logo</label>
                                        <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="logo"/>
                                    </div>
                                    <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Small modal-->
        </div>

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
                              <th scope="col">Nom court</th>
                              <!-- <th scope="col">Logo</th> -->
                              <th scope="col">Adresse</th>
                              <th scope="col">E-mail</th>
                              <th scope="col">Téléphone</th>
                              <th scope="col">Ville</th>
                              <th scope="col">Code Postal   </th>
                              <th scope="col">Voir</th>
                              <th scope="col">Modifier</th>
                              <th scope="col">supprimer</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($compagnie as $data)
                            <tr>
                              <td>{{$data->nom_complet}}</td>
                              <td>{{$data->abr}}</td>
                              <!-- <td>Pixel@efo.com	</td> -->
                              <td>{{$data->address}}	</td>
                              <td>{{$data->mail_1}}</td>
                              <td>{{$data->tel_1}}</td>
                              <td>{{$data->ville}}</td>
                              <td >{{$data->cp}}</td>
                              <td>
                                <form action="{{ url('profil-compagnie/'.$data->id)}}" method="get">
                                  <div class="form-group">
                                    <button type="submit" class="btn btn-info"> <i class="fa fa-reorder"></i></button>
                                  </div>
                                </form>
                              </td>
                              <td>
                                <form action="{{ url('edit-compagnie/'.$data->id)}}" method="get">
                                  <div class="form-group">
                                    <button type="submit" class="btn btn-warning"> <i class="fa fa-pencil"></i></button>
                                  </div>
                                </form>
                                <td>
                                <form action="{{ url('delete-compagnie/'.$data->id)}}" method="get">
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
    <script type="text/javascript">
        // var session_layout = '{{ session()->get('layout') }}';
    </script>
@endsection

@section('script')
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> 
@endsection
