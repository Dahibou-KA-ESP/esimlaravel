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
    <li class="breadcrumb-item active">Baremes Assurances</li>
@endsection

@section('content')
<div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
                
                <div class="col-xl-12">
                  
                    <div class="card-header">
                      <h4 class="card-title mb-0">modification bareme</h4>
                      <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                    </div>
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
                                    <div class="card-body">
                                        <form action="/update-bareme-saving/{{$bareme[0]->id}}" method="post">
                                        {{ csrf_field()}}
                                        @method('put')
                                        <div class="mb-3">
                                        <label class="col-form-label">Nom bareme</label>
                                        <input class="form-control" name="nom" value="{{$bareme[0]->nom}}" type="number" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">taux</label>
                                        <input class="form-control" name="taux" type="text" value="{{$bareme[0]->taux}}" required>
                                    </div>
                                        
                                            
                                            <div class="card-footer text-end">
                              <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
                              <button class="btn btn-primary" type="submit">Sauvegarder</button>
                            </div>
                                    
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