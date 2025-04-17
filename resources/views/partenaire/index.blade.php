@extends('layouts.master')
@section('title', 'User Cards')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Partenaires</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item active">Partenaires</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row starter-main">
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
                              <th >Partenaires</th>
                              <th >E-mail</th>
                              <th >Téléphone</th>
                              <th >Adresse</th>
                              <th >Voir client(s)</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($users as $data)
                            <tr>
                              <td>{{$data->civilite}}{{$data->first_name}} {{$data->last_name}}</td>
                              <td>{{$data->email}}</td>
                              <td>{{$data->phone}}</td>
                              <td>{{$data->address}}</td>
                              <td>
                                <form action="{{ url('partenaire-client/'.$data->id)}}" method="get">
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
</div>
@endsection

@section('script')

<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection