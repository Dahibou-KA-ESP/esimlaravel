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
<h3>Baremes Assurances</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">Baremes Assurances</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row starter-main">
        <div class="card-body btn-showcase">
            
                <!-- Large modal-->
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Ajouter</button>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="addcompagnie">Ajouter</h4>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <form action="/AddBareme" method="post" class="theme-form mega-form">
                                    {{ csrf_field()}}
                                    <h6>Baremes Assurances</h6>
                                    <div class="mb-3">
                                        <label class="col-form-label">Nom bareme</label>
                                        <input class="form-control" name="nom" type="text" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">taux</label>
                                        <input class="form-control" name="taux" type="text" placeholder="EX:0.225" required>
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
                  
                  <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                      <div class="table-responsive">
                        <table class="display" id="basic-1">
                          <thead>
                            <tr>
                              <th scope="col">Nom bareme</th>
                              <th scope="col">taux</th>
                              <th scope="col">Modifier</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($bareme as $data)
                            <tr>
                              <td>{{$data->nom}} mois</td>
                              <td>{{$data->taux}}</td>
                              <td>
                                <form action="{{ url('edit-bareme/'.$data->id)}}" method="get">
                                  <div class="form-group">
                                    <button type="submit" class="btn btn-warning"> <i class="fa fa-pencil"></i></button>
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
