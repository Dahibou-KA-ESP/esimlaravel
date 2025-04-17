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
<h3>Toutes les garantie</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">garanties</li>
@endsection

@section('content')
<div class="container-fluid">
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
    <div class="card-body btn-showcase">
                
                <!-- Large modal-->
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Nouvelle garantie</button>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="addcompagnie">Ajouter garantie</h4>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <form action="/AddGarantie" method="post" class="theme-form mega-form">
                                    {{ csrf_field()}}
                                    <h6>Garantie Assurance</h6>
                                    <div class="mb-3">
                                        <label class="col-form-label">Garanties</label>
                                        <input class="form-control" name="nom_garantie" placeholder="Responsabilité civile" type="text" required >
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Description</label>
                                        <input class="form-control" name="description"   type="text" required >
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label">Abreviation</label>
                                        <input class="form-control" name="nom_court" placeholder="rc" type="text" required>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label class="col-form-label">taux</label>
                                        <input class="form-control" name="taux" type="text" placeholder="EX:0.225">
                                    </div> -->
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
                <table class="display" id="basic-3">
                <thead>
                    <tr>
                    <th scope="col">Garantie</th>
                    <th scope="col">Nom court</th>
                    <th scope="col">Modifier</th>
                    <!-- <th scope="col">Suprimer</th> -->
                    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    @foreach($garantie as $data)
                    <td>{{$data->nom_garantie}}</td>
                    <td>{{$data->nom_court}}</td>
                    <td>
                        <form action="{{ url('edit-garantie/'.$data->id_garantie)}}" method="get">
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning"> <i class="fa fa-pencil"></i></button>
                        </div>
                        </form>
                    </td>
                    <!-- <td>
                        <form action="{{ url('delete-garantie/'.$data->id_garantie)}}" method="get">
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>
                        </div>
                        </form>
                    </td> -->
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
    
@endsection

@section('script')
<script type="text/javascript">
        // var session_layout = '{{ session()->get('layout') }}';
    </script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection