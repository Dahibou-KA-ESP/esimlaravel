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
<h3>Garanties Compagnies Assurances</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Tableau de bord</li>
    <li class="breadcrumb-item active">garanties</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-block row">
        <div class="col-sm-12 col-lg-12 col-xl-12">
          <div class="table-responsive">
            <table class="display" id="basic-1">
              <thead>
                <tr>
                  <th scope="col">Nom assurance</th>
                  <th scope="col">Catégorie Vehicule</th>
                  <th scope="col">Garantie</th>
                  <th scope="col">Type carrosserie</th>
                  <th scope="col">Force Fiscale</th>
                  <th scope="col">Energie</th>
                  <th scope="col">Prime</th>
                  <th scope="col">Remorque</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  @foreach($produit as $data)
                  <td>{{$data->compagnie->abr}}</td>
                  <td>{{$data->cat_vehicule}}</td>
                  <td>{{$data->garantie->nom_garantie }}</td>
                  <td>{{$data->type_carrosserie}}</td>
                  <td>{{$data->force_fiscale}}</td>
                  <td>{{$data->energie}}</td>
                  <td>{{$data->prime}}</td>
                  <td >{{$data->remorque}}</td>
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