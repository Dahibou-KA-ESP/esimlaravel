@extends('layouts.master')
@section('title', 'User Cards')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Tous les utilisateurs </h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@section('content')
<div class="container-fluid">
    <!--modal-->
        <div class="text-end mb-2">
            <button class="btn btn-primary " type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Nouvel utilisateur</button>
        </div>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addcompagnie">Ajouter utilisateur</h4>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <form action="/AddUser" method="post" class="theme-form mega-form" enctype="multipart/form-data">
                                {{ csrf_field()}}
                                    <div class="card-header">
                                    <h4 class="card-title mb-0">Informations</h4>
                                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">
                                
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Civilite</label>
                                            <select  name="civilite"  class="form-select" required >
                                                    <option selected="" value="M." >Monsieur</option>
                                                    <option   value="Mme">Madame</option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prénom</label>
                                            <input class="form-control" name="first_name"  type="text" required >
                                        </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input class="form-control" name="last_name" type="text" required  >
                                        </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email address</label>
                                            <input class="form-control" name="email" type="email" required >
                                        </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Telephone</label>
                                            <input class="form-control" name="phone" type="number" placeholder="221778100915" required>
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input class="form-control" name="address" type="text"  required>
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="date-picker">
                                            <label class="form-label">Date de naissance</label>
                                            <input class="datepicker-here form-control digits" name="birthday" type="text"   data-language="fr" required>
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rôle</label>
                                            <select id="role"  name="role"  class="form-select" required>
                                                    <option selected="" value="staff" >Staff</option>
                                                    @auth
                                                    @if(Auth::check() &&  Auth::user()->role === 'super')
                                                    <option   value="admin">Admin</option>
                                                    <option   value="partenaire">Partenaire</option>
                                                    <option   value="social">Social</option>
                                                    @endauth
                                                    @endif
                                            </select>
                                        </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Photo profile</label>
                                                <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload"  data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image_filename"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                        <div class="mb-3">
                                            <label id="pourcentageLabel" class="form-label">Pourcentage(%)</label>
                                            <input id="pourcentage" class="form-control" name="commission_rate" type="number" placeholder="10" required>
                                        </div>
                                        </div>
                                        <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mot de passe</label>
                                            <input type="password"  name="password" class="form-control" required  >
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirmez le mot de passe</label>
                                            <input type="password"  class="form-control" name="conf_password" required  >
                                        </div>
                                        </div> -->
                                    
                                    </div>
                                    </div>
                                    <div class="card-footer text-end">
                                    <button class="btn btn-primary" type="submit">créer utilisateur</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--modal-->

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
            @if (Auth::check() && Auth::id() == $users[0]->id && $all->role != 'super')
                <div class="form-check form-switch ">
                    <input class="form-check-input" type="checkbox" role="switch" id="user_{{$all->id}}"  onclick="toggleUserStatus(<?php echo $all->id; ?>)" {{ $all->user_actif == 1 ? 'checked' : '' }}  >
                    <!-- <label class="form-check-label" for="flexSwitchCheckChecked">Activer/Desactiver</label> -->
                </div>
            @endif
                <div class="card-body">
                    <div class="social-img-wrap">
                        <div class="social-img">
                            <img style="height: 80px;width:80px" src="{{ asset('storage/image/profile_image/'.$all->profile_image_filename) }}" alt="profile">
                        </div>
                        <div class="edit-icon">
                            <svg>
                            <use href="{{ asset('assets/svg/icon-sprite.svg#profile-check') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="social-details">
                        <h5 class="mb-1"><a href="">{{$all->first_name}} {{$all->last_name}}</a></h5>
                        <span class="f-dark">Rôle: {{$all->role}}</span><br>
                        <span class="f-dark">{{$all->email}}</span><br>
                        <span class="f-dark">{{$all->phone}}</span><br>
                        <span class="f-dark">{{$all->address}}</span><br>
                        <div class="d-flex flex-row justify-content-between mt-2">
                        @if ($all->role != 'super')
                            <form action="{{ url('delete-user/'.$all->id)}}" method="get" >
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>
                                </div>
                            </form>
                        @endif
                            <form action="{{ url('edit-user/'.$all->id)}}"  method="get">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary "> <i class="fa fa-eye"></i></button>
                                </div>
                            </form>
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
<script>
    function toggleUserStatus(iduser) {
        $('#user_'+iduser).change(function() {
            var isChecked = $(this).prop('checked');
            var userId = iduser // Remplacez par l'ID réel du package
            
            // Envoyer une requête AJAX pour mettre à jour le statut du package
            $.ajax({
                url: '/update-user-status/'+userId,
                method: 'POST',
                data: { user_actif: isChecked ? 1 : 0 ,
                        _token:"{{csrf_token()}}"
                }, // 1 pour activer, 0 pour désactiver
                success: function(response) {
                    // console.log(response.success)
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la mise à jour du statut  :', error);
                }
            });
        });
    }


    function toggleSecondSelect() {
        var firstSelectValue = $('#role').val();
        if (firstSelectValue === 'partenaire') {
            $('#pourcentageLabel, #pourcentage').show().prop('required', true);
           

        } else {
            $('#pourcentageLabel, #pourcentage').hide().prop('required', false);
           

        }
    }
    // Initial toggle based on the default value of the first select
    toggleSecondSelect();
    // Event listener to toggle the second select when the first select changes
    $('#role').on('change', toggleSecondSelect);
</script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection