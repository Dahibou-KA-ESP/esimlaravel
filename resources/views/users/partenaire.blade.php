@extends('layouts.master')
@section('title', 'User Cards')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Tous les utilisateurs partenaires</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item active">Utilisateurs partenaires</li>
@endsection

@section('content')
<div class="container-fluid">
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
        @foreach($users as $all )
        <div class="col-xl-4 col-sm-6 col-xxl-3 col-ed-4 box-col-4">
            <div class="card social-profile">
                
                <div class="card-body">
                    <div class="social-img-wrap">
                        <div class="social-img">
                            <img  src="{{ asset('storage/image/profile_image/'.$all->profile_image_filename) }}" alt="profile">
                        </div>
                        <!-- <div class="edit-icon">
                            <svg>
                            <use href="{{ asset('assets/svg/icon-sprite.svg#profile-check') }}"></use>
                            </svg>
                        </div> -->
                    </div>
                    <div class="social-details">
                        <h5 class="mb-1"><a href="">{{$all->first_name}} {{$all->last_name}}</a></h5>
                        <span class="f-dark">Rôle: {{$all->role}}</span><br>
                        <span class="f-dark">{{$all->email}}</span><br>
                        <span class="f-dark">{{$all->phone}}</span><br>
                        <span class="f-dark">{{$all->address}}</span><br>
                        <div class="flex-row mt-2">
                            <form action="{{ url('edit-user/'.$all->id)}}"  method="get">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary "> <i class="fa fa-eye"></i></button>
                                </div>
                            </form>
                            <!-- <form action="{{ url('acive-user/'.$all->id)}}" method="get" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>
                                </div>
                            </form> -->
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @endforeach
	</div>
    
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection