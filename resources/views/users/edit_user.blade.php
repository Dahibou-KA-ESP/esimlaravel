@extends('layouts.master')
@section('title', 'User Cards')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Utilisateurs</li>
<li class="breadcrumb-item active">modifier</li>
@endsection

@section('content')

<!-- Large modal add produit auto-->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addcompagnie" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
              <h4 class="modal-title" id="addproduit">Update photo</h4>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="card">
                      <div class="card-body">
                          <form action="/update-user-profile-saving/{{$users[0]->id}}" method="POST" enctype="multipart/form-data" >
                          {{ csrf_field()}}
                          @method('put')
                              <div class="row g-3">
                                  
                              <div class="col-md-6">
                              <div class="mb-3">
                                  <label>Photo profil</label>
                                  <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload"  data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image_filename"/>
                              </div>
                          </div>
                                  
                                  
                                  
                                  
                                  
                              </div>
                              <div class="row g-3">
                              
                              
                                  
                                  
                                  <button class="btn btn-primary" type="submit">Enregistrer</button>
                              </div>
                              
                          </form>
                      </div>
                  </div>
              </div>
                  
          </div>
      </div>
</div>
            <!--end modal-->

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
        <div class="col-xl-4">
        
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Profil</h4>
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <form>
                <div class="row mb-2">
                  <div class="profile-title">
                    <div class="media">
                        <img style="height: 80px;width:100px" class="img-70 rounded-circle" alt=""  src="{{ asset('storage/image/profile_image/'. $users[0]->profile_image_filename) }}" >
                        <div class="icon-wrapper">
                        <a  type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"><i class="icofont icofont-pencil-alt-5"></i></a>
                        </div>
                      <div class="media-body">
                        <h5 class="mb-1">{{$users[0]->first_name}} {{$users[0]->last_name}}</h5>
                        <p>Rôle: {{$users[0]->role}}</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label class="form-label">E-mail</label>
                  <input class="form-control" value="{{$users[0]->email}}" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Téléphone</label>
                  <input class="form-control" value="{{$users[0]->phone}}" readonly>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-xl-8">
          <form methode='post' action="/update-user-saving/{{$users[0]->id}}" class="card">
            {{ csrf_field()}}
            @method('put')
            <div class="card-header">
              <h4 class="card-title mb-0">Modifier Profil</h4>
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <div class="row">
                <!-- <div class="col-md-5">
                  <div class="mb-3">
                    <label class="form-label">Company</label>
                    <input class="form-control" type="text" placeholder="Company">
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" placeholder="Username">
                  </div>
                </div> -->
                
                <div class="col-sm-6 col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Prénom(s)</label>
                    <input class="form-control" name="first_name" value='{{$users[0]->first_name}}' type="text" required >
                  </div>
                </div>
                <div class="col-sm-6 col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="last_name" type="text"  value='{{$users[0]->last_name}}' required>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6">
                  <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input class="form-control" name="email" type="email"  value='{{$users[0]->email}}' required>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input class="form-control" name="phone" type="text"  value='{{$users[0]->phone}}'>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input class="form-control" name="address" type="text"  value='{{$users[0]->address}}'>
                  </div>
                </div>
                @auth
                @if(Auth::check() &&  Auth::user()->role === 'super')
                <div class="col-md-6">
                  <div class="mb-3">
                      <label class="form-label">Rôle</label>
                      <select id="role" name="role"  class="form-select" required>
                              <option selected="" value="{{$users[0]->role}}" >{{$users[0]->role}}</option>
                              <option  value="staff" >Staff</option>
                              <option   value="admin">Admin</option>
                              <option   value="partenaire">Partenaire</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label id="pourcentageLabel" class="form-label">Pourcentage(%)</label>
                    <input id="pourcentage" class="form-control" name="commission_rate" type="number"  value='{{$users[0]->commission_rate}}'>
                  </div>
                </div>
                @endif
                @endauth
                @if(Auth::check() && Auth::id() == $users[0]->id || Auth::user()->role === 'super')
                <div class="col-md-6">
                  <div class="date-picker">
                    <label class="form-label">Date de naissance</label>
                    <input class="datepicker-here form-control digits" name="birthday" type="text" value='{{$users[0]->birthday }}'  data-language="fr">
                  </div>
                </div>
                @endif
                @if(Auth::check() && Auth::id() == $users[0]->id)
                <div class="col-md-6">
                  <div class="mb-3">
                      <label class="form-label">Modifier mot de passe</label>
                      <input type="password"  name="update_password" class="form-control">
                  </div>
                </div>
                @endif
                <!-- <div class="col-md-5">
                  <div class="mb-3">
                    <label class="form-label">Country</label>
                    <select class="form-control btn-square">
                      <option value="0">--Select--</option>
                      <option value="1">Germany</option>
                      <option value="2">Canada</option>
                      <option value="3">Usa</option>
                      <option value="4">Aus</option>
                    </select>
                  </div>
                </div> -->
                <!-- <div class="col-md-12">
                  <div>
                    <label class="form-label">About Me</label>
                    <textarea class="form-control" rows="4" placeholder="Enter About your description"></textarea>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="card-footer text-end">     
              <!-- <a href="{{ url()->previous() }}" class="btn btn-danger">Reactiver</a>  -->
               <a href="{{ url()->previous() }}" class="btn btn-danger">Retour</a>
              <button class="btn btn-primary" type="submit">Enregistrer</button>
            </div>
          </form>
          @if(Auth::check() && Auth::id() !== $users[0]->id)
          <form class="theme-form" method="POST" action="{{ route('password.email') }}">
            @csrf
              <input id="email" hidden type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$users[0]->email}}"  required autocomplete="email" autofocus>
                <button type="submit" class="btn btn-primary">Envoyer lien de reinitialisation</button>
          </form>
          @endif
        </div>
      </div>
    </div>




</div>
@endsection


@section('script')
<script>
  function toggleSecondSelect() {
        var firstSelectValue = $('#role').val();
        if (firstSelectValue === 'partenaire') {
            $('#pourcentageLabel, #pourcentage').show().prop('required', true);
           

        } else {
            $('#pourcentage').val(null); // Définit la valeur à NULL
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