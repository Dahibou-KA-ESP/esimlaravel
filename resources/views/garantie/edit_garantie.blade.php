@extends('layouts.master')

@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Baremes Assurances</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Garantie Assurance</li>
@endsection

@section('content')
<div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
                
                <div class="col-xl-12">
                  
                    <div class="card-header">
                      <h4 class="card-title mb-0">modifier garantie</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card">
                        <div class="card-body">
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
                            <form action="/update-garantie-saving/{{$garantie[0]->id_garantie}}" method="post">
                                {{ csrf_field()}}
                                @method('put')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Nom garantie</label>
                                        <input class="form-control" name="nom_garantie" type="text" value="{{$garantie[0]->nom_garantie}}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Abreviation</label>
                                        <input class="form-control" name="nom_court" type="text" value="{{$garantie[0]->nom_court}}" readonly>
                                    </div>
                                </div>
                                    
                                    <div class="mb-3">
                                        <label class="col-form-label">Description</label>
                                        <textarea class="form-control" name="description" >{{$garantie[0]->description}}</textarea>
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
        </div>
@endsection

@section('script')

@endsection