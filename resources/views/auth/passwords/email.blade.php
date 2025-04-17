
@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-xl-7"><img class="bg-img-cover bg-center" src="{{asset('assets/images/login/6.svg')}}" alt="looginpage"></div>
      <div class="col-xl-5 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo text-start" href=""><center><img class="img-fluid for-light" style="width: 30%;" src="{{ asset('assets/images/platine.png') }}" alt="looginpage"></center><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form class="theme-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                     <h4>reinitialisation mot de passe !</h4>
                     <p>Renseignez votre email</p>
                     <div class="form-group">
                        <label class="col-form-label">E-mail</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                     </div>
                     <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">Envoyer lien de reinitialisation</button>
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