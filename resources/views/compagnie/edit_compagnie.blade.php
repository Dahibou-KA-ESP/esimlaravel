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
                <div class="col-xl-12">
                  <form methode='post' action="/update-compagnie-saving/{{$compagnie[0]->id}}" class="card" enctype="multipart/form-data">
                   {{ csrf_field()}}
                    @method('put')
                    <div class="card-header">
                      <h4 class="card-title mb-0">Modification compagnie</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">Nom assurance</label>
                            <input class="form-control" name="nom_complet" value="{{$compagnie[0]->nom_complet }}" type="text" placeholder="Nom assurance">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">Addresse</label>
                            <input class="form-control" name="address" value="{{$compagnie[0]->address }}" type="text" placeholder="Entrer une adrresse">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                              <label class="col-form-label">Ville</label>
                              <input class="form-control" name="ville" value="{{$compagnie[0]->ville }}" type="text" placeholder="Ex:Dakar">
                          </div>
                        </div>  
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">E-mail</label>
                            <input class="form-control"name="mail_1" type="email" value="{{$compagnie[0]->mail_1 }}" placeholder="Entrer email">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">E-mail 2</label>
                            <input class="form-control"name="mail_2" type="email" value="{{$compagnie[0]->mail_2 }}" placeholder="Entrer email">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">E-mail 3</label>
                            <input class="form-control"name="mail_3" type="email" value="{{$compagnie[0]->mail_3 }}" placeholder="Entrer email">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">Téléphone</label>
                            <input class="form-control" name="tel_1" type="number" value="{{$compagnie[0]->tel_1 }}" placeholder="Numero telephone">
                          </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <div class="mb-3">
                            <label class="col-form-label">Téléphone 2</label>
                            <input class="form-control" name="tel_2" value="{{$compagnie[0]->tel_2 }}" type="number" placeholder="Numero telephone">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                              <label class="col-form-label">Code Postal</label>
                              <input class="form-control" name="cp" value="{{$compagnie[0]->cp }}" type="number" placeholder="Ex:33655">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                              <label class="col-form-label">Fax</label>
                              <input class="form-control" value="{{$compagnie[0]->fax }}" name="fax" type="text" >
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                              <label class="col-form-label">Abréviation</label> 
                              <input class="form-control" value="{{$compagnie[0]->abr }}" name="abr" type="text" >
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="mb-3">
                              <label>Logo</label>
                              <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload"  data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image_filename"/>
                          </div>
                        </div>

                        
                        
                      </div>
                    </div>
                    <div class="card-footer text-end">
                      <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                      <button class="btn btn-primary" type="submit">Sauvegarder</button>
                    </div>
                  </form>
                </div>
                
              </div>
            </div>
          </div>
        </div>
@endsection

@section('script')

@endsection